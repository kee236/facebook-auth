<?php

namespace App\Services;

use Exception;
use App\Models\Post;
use App\Models\User;
use Facebook\Facebook;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Response;
use Laravel\Socialite\Facades\Socialite;
use Facebook\Exceptions\FacebookSDKException;
use Illuminate\Contracts\Filesystem\Filesystem;
use Facebook\Exceptions\FacebookResponseException;
use Laravel\Socialite\Contracts\User as SocialiteUser;

class FacebookService
{

    public function __construct(Facebook $facebook)
    {
        $this->facebook = $facebook;
    }



    public function redirectToFacebook()
    {
        return Socialite::driver('facebook')
            ->scopes([
                "public_profile",
                "publish_pages",
                "publish_actions, manage_pages",
                "pages_show_list",
                "pages_read_engagement",
                "pages_manage_posts",
                "pages_manage_metadata",
                'pages_manage_engagement',
                'pages_messaging',
                "user_videos",
                "user_posts"
            ])
            ->redirect();
    }

    public function handleFbCallback(SocialiteUser $authUser, User $userModel, Filesystem $filesystem)
    {
                try {
                    $user = $this->createOrUpdateUser($authUser, $userModel);
                    return $user;
                    } catch (\Exception $e) {
                        Log::error($e->getMessage());
                        throw $e;
                    }
    }





    private function createOrUpdateUser(SocialiteUser $authUser, User $userModel)
    {

        // Check if a user with the same email already exists in your database
        $user = $userModel->where('email', $authUser->email)->first();

        if ($user) {
            // User already exists, update the Facebook ID and name
            $user->facebook_id = $authUser->id;
            $user->name = $authUser->name;

            // Fetch the user's Facebook page name using the Graph API
            $facebookPageData = $this->fetchFacebookPages($authUser->token);

            // Check if $facebookPageData is an array
            if (is_array($facebookPageData) && !empty($facebookPageData)) {
                foreach ($facebookPageData as $page) {
                    $id = $page['id'];
                    $name = $page['name'];
                    $user->facebook_page_id = $id;
                    $user->facebook_page_name = $name;
                }
            }

            // Encrypt and save the User Access Token
            $encryptedAccessToken = Crypt::encrypt($authUser->token);
            $user->facebook_access_token = $encryptedAccessToken;

            $user->save();
            return redirect()->route('facebook.index');
        } else {
            // User doesn't exist, create a new user
            $user = new $userModel([
                'name' => $authUser->name,
                'email' => $authUser->email,
                'facebook_id' => $authUser->id,
            ]);

            // Encrypt and save the User Access Token
            $encryptedAccessToken = Crypt::encrypt($authUser->token);
            $user->facebook_access_token = $encryptedAccessToken;
            $user->save();
        }
        return redirect()->route('login');
    }


    private function fetchFacebookPages($accessToken)
    {
        $pages = [];

        // Initialize the first page URL
        $url = "https://graph.facebook.com/v18.0/me/accounts?fields=id,name&access_token={$accessToken}";

        // Use a loop to handle pagination
        while ($url) {
            $response = Http::get($url);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['data']) && is_array($data['data'])) {
                    foreach ($data['data'] as $pageData) {
                        $pages[] = [
                            'id' => $pageData['id'],
                            'name' => $pageData['name'],
                        ];
                    }
                }

                // Check if there are more pages to retrieve
                $url = isset($data['paging']['next']) ? $data['paging']['next'] : null;
            } else {
                    $errorCode = $response->status();
                    $errorMessage = $response->body();
                    Log::error("Facebook API request failed with status code {$errorCode}: {$errorMessage}");
                    throw new \Exception("Facebook API request failed with status code {$errorCode}: {$errorMessage}");
                    return null;
            }
        }

        return $pages;
    }



    public function post($accountId, $accessToken, $content, $images = [])
    {
        $data = ['message' => $content];

        $attachments = $this->facebook->uploadImages($accountId, $accessToken, $images);

        foreach ($attachments as $i => $attachment) {
            $data["attached_media[$i]"] = "{\"media_fbid\":\"$attachment\"}";
        }

        try {
            return $this->postData($accessToken, "$accountId/feed", $data);
        } catch (\Exception $exception) {
            return false;
        }
    }

    private function uploadImages($accountId, $accessToken, $images = [])
    {
        $attachments = [];

        foreach ($images as $image) {
            if (!file_exists($image)) continue;

            $data = [
                'source' => $this->fileToUpload($image),
            ];

            try {
                $response = $this->postData($accessToken, "$accountId/photos?published=false", $data);
                if ($response) $attachments[] = $response['id'];
            } catch (\Exception $exception) {
                throw new \Exception("Error while posting: {$exception->getMessage()}", $exception->getCode());
            }
        }

        return $attachments;
    }

    private function postData($accessToken, $endpoint, $data)
    {
        try {
            $response = $this->post(
                $endpoint,
                $data,
                $accessToken
            );
            return $response->getGraphNode();
        } catch (FacebookResponseException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        } catch (FacebookSDKException $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }




    public function publishToPage(Request $request)
    {
        $pageId = Auth::user()->facebook_page_id ?? '';
        $status_code = 200;
        $msg = '';

        try {
            if ($pageId && Auth::user()->token) {
                $id = decrypt($request->id);
                $getData = Post::findOrFail($id);

                if (in_array($getData->file_type, ["mp4", "mov", "wmv", "avi", "avchd", "flv", "swf", "f4v", "mkv", "webm", "html5", "mpeg-2"])) {
                    $contentType = "videos";
                    $contentField = "description";
                } else {
                    $contentType = "photos";
                    $contentField = "message";
                }

                if ($getData->fb_post_id) {
                    $post = $this->updateFacebookPost('/' . $getData->fb_post_id, [$contentField => $getData->message], $pageId);
                } else {
                    $post = $this->createFacebookPost('/' . $pageId . '/' . $contentType, [$contentField => $getData->message, 'source' => $this->uploadImagesToFacebook(public_path('images/' . $getData->image))], $pageId);
                }

                if ($post) {
                    $status_code = 200;
                    $msg = $getData->fb_post_id ? 'Updated post on Facebook successfully' : 'Created post on Facebook successfully';
                } else {
                    $msg = $getData->fb_post_id ? 'Your post was not updated on Facebook.' : 'Your post was not created on Facebook.';
                    $status_code = 400;
                }
            }
            $arr = array("status" => $status_code, "msg" => $msg);
        } catch (QueryException $ex) {
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            }
            $status_code = 400;
            $arr = array("status" => 400, "msg" => $msg, "result" => array());
        } catch (Exception $ex) {
            if ($ex->getCode() == 100) {
                $id = decrypt($request->id);
                $getData = Post::findOrFail($id);
                $getData->fb_post_id = null;
                $getData->fb_id = null;
                $getData->save();
            }
            $msg = $ex->getMessage();
            if (isset($ex->errorInfo[2])) {
                $msg = $ex->errorInfo[2];
            }
            $status_code = 400;
            $arr = array("status" => 400, "msg" => $msg, 'line' => $ex->getLine(), "result" => array());
        }

        return Response::json($arr);
    }



    private function createFacebookPost($endpoint, $data, $pageId)
    {
        return $this->postData($endpoint, $data, $this->getPageAccessToken($pageId));
    }

    private function updateFacebookPost($endpoint, $data, $pageId)
    {
        return $this->postData($endpoint, $data, $this->getPageAccessToken($pageId));
    }


    private function getPageAccessToken($pageId)
    {
        try {
            $response = $this->facebook->get('me/accounts', Auth::user()->token);
        } catch (FacebookResponseException $e) {
            Log::error('Graph returned an error: ' . $e->getMessage());
            throw $e;
        } catch (FacebookSDKException $e) {
            Log::error('Facebook SDK returned an error: ' . $e->getMessage());
            throw $e;
        }

        try {
            $pages = $response->getGraphEdge()->asArray();
            foreach ($pages as $page) {
                if ($page['id'] === $pageId) {
                    return $page['access_token'];
                }
            }
        } catch (FacebookSDKException $e) {
            Log::error('Error while fetching page access token: ' . $e->getMessage());
            throw $e;
        }
        Log::error('Page access token not found for page ID: ' . $pageId);
        return null;
    }



    private function uploadImagesToFacebook($imagePaths)
    {
        $uploadedImages = [];

        foreach ($imagePaths as $imagePath) {
            $uploadedImage = $this->fileToUpload($imagePath);
            $uploadedImages[] = $uploadedImage;
        }

        return $uploadedImages;
    }

    private function fileToUpload($imagePath)
    {
        $fileName = time() . '_' . pathinfo($imagePath, PATHINFO_FILENAME) . '.' . pathinfo($imagePath, PATHINFO_EXTENSION);
        $uploadedImages[] = '/storage/images/' . $fileName;

        return $this->facebook->fileToUpload($imagePath);
    }


    # Auto Reply
    public function sendMessage($recipientId, $message)
    {
        try {
            $pageAccessToken = $this->getPageAccessToken($pageId);

            // Prepare the message payload
            $messageData = [
                'recipient' => [
                    'id' => $recipientId,
                ],
                'message' => [
                    'text' => $message,
                ],
            ];

            // Make an HTTP POST request to the Messenger API
            $response = Http::post('https://graph.facebook.com/v18.0/me/messages', [
                'access_token' => $pageAccessToken,
                'messaging_type' => 'RESPONSE', // Or other messaging types as needed
                'message' => $messageData,
            ]);

            // Check if the request was successful
            if ($response->successful()) {
                Log::info('Message sent successfully to recipient ' . $recipientId);
                return true;
            } else {
                Log::error('Error sending message: ' . $response->body());
                return false;
            }
        } catch (\Exception $e) {
            Log::error('General error sending message: ' . $e->getMessage());
            return false;
        }
    }







    public function associateKeywordWithAutoReply($keywordId, $message, $type)
    {
        try {
            $autoReply = new AutoReply([
                'keyword_id' => $keywordId,
                'message' => $message,
                'type' => $type,
            ]);
            $autoReply->save();
            return $autoReply;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return null;
        }
    }








}
