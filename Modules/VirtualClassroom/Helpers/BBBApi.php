<?php

namespace Modules\VirtualClassroom\Helpers;

use App\User;
use BigBlueButton\Parameters\CreateMeetingParameters;
use JoisarJignesh\Bigbluebutton\Facades\Bigbluebutton;

class BBBApi
{
    protected $meeting_id;
    protected $meeting_name;

    public function __construct($meeting_id, $meeting_name = 'Class Name')
    {
        $this->meeting_id = $meeting_id;
        $this->meeting_name = $meeting_name;
    }

    public function create() {
        $meetingParams = new CreateMeetingParameters($this->meeting_id, $this->meeting_name);
        $meetingParams->setModeratorPassword('moderator');
        $meetingParams->setAttendeePassword('attendee');
        $meetingParams->setRecord(true);
        $meetingParams->setAllowStartStopRecording(true);

        return Bigbluebutton::create($meetingParams);
    }

    public function close() {
        Bigbluebutton::close([
            'meetingID' => $this->meeting_id,
            'moderatorPW' => 'moderator'
        ]);
    }

    public function join($user_id, $role = 'attendee') {
        $user = User::find($user_id);

        $join_url = Bigbluebutton::join([
            'meetingID' => $this->meeting_id,
            'userName' => $user->username,
            'password' => $role,
            'redirect' => true,
            'userId' =>  $user->id,
        ]);

        return $join_url;
    }

    public function isRuning() {
        return self::isMeetingRunning($this->meeting_id);
    }

    public static function isMeetingRunning($meeting_id) {
        return Bigbluebutton::isMeetingRunning($meeting_id);
    }

    public static function isConnect() {
        return Bigbluebutton::isConnect();
    }
}
