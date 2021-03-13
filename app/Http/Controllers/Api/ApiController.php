<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\EventList;
use App\Models\EventMemberList;
use App\Models\EventImages;

use App\Http\_Helpers\ApiResponseHelpers as helperFile;


class ApiController extends Controller
{
    public $response ;
    public function __construct()
    {
        $this->response = new helperFile();
    }

    public function register(Request $request) {
        try {
            $validation = \Validator::make(\Request::all(),[
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|unique:users,email',
                'phone' => 'required|unique:users,phone',
                'dateOfBirth' => 'date_format:Y-m-d',
                'password' => 'required|min:6',
                'occupation' => '',
            ]);

            if($validation->fails()){
                return $this->response->jsonErrorResponse([], implode(",", $validation->errors()->all()));
            } else {
                $user = new User();
                $user->first_name = $request->get('firstName');
                $user->email = $request->get('email');
                $user->last_name = $request->get('lastName');
                $user->phone = $request->get('phone');
                $user->date_of_birth = $request->get('dateOfBirth');
                $user->occupation = $request->get('occupation');
                $user->password = Hash::make($request->get('password'));
                $user->api_token = \Str::random(60);
                $user->role = 2;
                $user->save();

                $accessToken = $user->createToken('authToken')->accessToken;
                $data['user'] = User::findOrFail($user->id);
                $data['accessToken'] = $accessToken;

                return $this->response->jsonSuccessResponse($data, "Your account has been created successfully!!");
            }
        } catch (Exception $e) {
            return $this->response->jsonErrorResponse([],"Something went wrong");
        }
    }

    public function Login(Request $request) {
        try {
            $validation = \Validator::make(\Request::all(),[
                'email' => 'required',
                'password' => 'required'
            ]);

            if($validation->fails()){
                return $this->response->jsonErrorResponse([], implode(",", $validation->errors()->all()));
            } else {
                if(\Auth::attempt(['email' => $request->get('email'), 'password' => $request->get('password')])) {
                    return $this->response->jsonSuccessResponse(User::where("email",$request->get('email'))->first(), "You have logged in to your account successfully!!");
                } else {
                    return $this->response->jsonErrorResponse([], "Unmatched Username and password");
                }
            }
        } catch (Exception $e) {
            return $this->response->jsonErrorResponse([],"Something went wrong");
        }
    }

    public function joinEvent(Request $request) {
        try {
            $validation = \Validator::make(\Request::all(),[
                'userId' => 'required',
                'eventId' => 'required'
            ]);

            if($validation->fails()){
                return $this->response->jsonErrorResponse([], implode(",", $validation->errors()->all()));
            } else {
                $EventList = EventList::where("id",$request->get('eventId'))->first();
                $eventMemberCount = EventMemberList::where("event",$request->get('eventId'))->get();
                $isMemberExist = EventMemberList::where("event",$request->get('eventId'))->where("user", $request->get('userId'))->get();
                if($EventList != null) {
                    if(count($isMemberExist) > 0) {
                        return $this->response->jsonErrorResponse([], "You have already joined to ". $EventList->name . " event successfully!!");
                    } else {
                        if($EventList->member_limit >= count($eventMemberCount)) {
                            $EventMemberList = new EventMemberList();
                            $EventMemberList->user = $request->get('userId');
                            $EventMemberList->event = $request->get('eventId');
                            $EventMemberList->save();
                            return $this->response->jsonSuccessResponse([], "You have joined to ". $EventList->name . " event successfully!!");
                        } else {
                            return $this->response->jsonErrorResponse([], "Unable to join, Event is full!!");
                        }
                    }
                } else {
                    return $this->response->jsonErrorResponse([], "Event not found!!");
                }
            }
        } catch (\Exception $e) {
            return $this->response->jsonErrorResponse([],"Something went wrong");
        }
    }

    public function eventDetail(Request $request) {
        try {
            $validation = \Validator::make(\Request::all(),[
                'eventId' => 'required'
            ]);

            if($validation->fails()){
                return $this->response->jsonErrorResponse([], implode(",", $validation->errors()->all()));
            } else {
                $EventList = EventList::where("id",$request->get('eventId'))->first();
                if($EventList != null) {
                    $EventList['additionalImage'] = EventImages::where("event", $EventList->id)->get();
                    return $this->response->jsonSuccessResponse($EventList, "Success");
                } else {
                    return $this->response->jsonErrorResponse([], "Event not found!!");
                }
            }
        } catch (\Exception $e) {
            return $this->response->jsonErrorResponse([],"Something went wrong");
        }
    }
}
