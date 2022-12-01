<?php
namespace App\Channels\EmailParser;

use App\Channels\BaseChannel;
use Sunra\PhpSimple\HtmlDomParser;
use App\Classes\WriteLog;
/**
 * Created by Gopal.
 *
 * Descriptions : Simple incoming mail parse 
 * 
 */

class Parser {

	protected $parser;
	protected $data;

    const XPLACE = 'xplace';
    const FREELANCER = 'freelancer';
    const EMAIL = 'email';

    const DATA_TYPE_REPLY = 'reply';
    const DATA_TYPE_NEW_PROJECT = 'project';

    public function __construct($data){
		$this->data = $data;
        $this->parser = $this->getParser($data);
	}

	public function parseData() {
        return $this->parser->parseData($this->data);
    }

    protected function getParser($data) {
            if (XplaceParser::detectParser($data)) {
                return new XplaceParser();
            }

            return new NewEmailParser();
    }

    public static function isSuccess($data) {
        return $data['success'];
    }
}