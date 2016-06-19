<?php namespace SiegerHansma\MailhogTestcase;

use GuzzleHttp\Client;

class MailhogTestCase extends \TestCase
{
    protected $mailhog;

    protected $mailhogBasepath = 'http://localhost:8025';

    /**
     * MailTestCase constructor.
     */
    public function __construct()
    {
        $this->mailhog = new Client(['base_uri' => $this->mailhogBasepath . '/api/']);
    }

    /**
     * Gets a list of all emails.
     * @return mixed
     */
    public function getAllEmails()
    {
        $emails = $this->mailhog->get('v1/messages')->getBody()->getContents();

        return json_decode($emails);
    }

    /**
     * Checks if the number of emails expected is equal to the number of emails in the mailbox.
     *
     * @param $expected
     *
     * @return $this
     *
     */
    public function assertNumberOfEmails($expected)
    {
        $emails = $this->getAllEmails();
        $this->assertCount($expected, $emails);

        return $this;
    }

    /**
     * Provides a var_dump of the contents of the mailbox.
     * @return $this
     */
    public function dumpEmails()
    {
        var_dump($this->getAllEmails()['items']);

        return $this;
    }

    /**
     * Compares number of expected mails to the number of actual mails send to an emailaddress.
     * @param $email
     * @param $expected
     *
     * @return $this
     * @throws Exception
     */
    public function seeNumberOfEmailsTo($email, $expected)
    {
        $emails = $this->search('to', $email);

        $this->assertCount($expected, $emails);

        return $this;

    }

    /**
     * Clears out the mailbox.
     * @return $this
     */
    public function resetEmails()
    {
        $this->mailhog->delete('v1/messages');

        return $this;
    }

    /**
     * Searches inside of the mailbox. 
     * @param $kind
     * @param $query
     *
     * @return mixed
     * @throws Exception
     */
    private function search($kind, $query)
    {
        if (! in_array($kind, ['from', 'to', 'containing'])) {
            throw new Exception('Kind can only be from, to or containing.');
        }

        if (! $query) {
            throw new Exception('We need a query to do a search.');
        }

        $emails = $this->mailhog->get('v2/search', ['kind' => $kind, 'query' => $query])->getBody()->getContents();

        return json_decode($emails);
    }

}
