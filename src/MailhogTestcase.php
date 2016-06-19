<?php namespace SiegerHansma\MailhogTestcase;

class MailhogTestCase extends \TestCase
{
    protected $mailhog;

    protected $mailhogBasepath = 'http://localhost:8025';

    /**
     * MailTestCase constructor.
     */
    public function __construct()
    {
        $this->mailhog = new \GuzzleHttp\Client(['base_url' => $this->mailhogBasepath . '/api/']);
    }

    public function getAllEmails()
    {
        $emails = $this->mailhog->get('v1/messages')->json();

        return $emails;
    }

    public function assertNumberOfEmails($count)
    {
        $emails = $this->getAllEmails();
        $this->assertCount($count, $emails);

        return $this;
    }

    public function dumpEmails()
    {
        var_dump($this->getAllEmails()['items']);

        return $this;
    }

    public function seeNumberOfEmailsTo($email, $expected)
    {
        $emails = $this->search('to', $email);

        $this->assertCount($expected, $emails);

        return $this;

    }

    public function resetEmails()
    {
        $this->mailhog->delete('v1/messages');

        return $this;
    }

    private function search($kind, $query)
    {
        if (! in_array($kind, ['from', 'to', 'containing'])) {
            throw new Exception('Kind can only be from, to or containing.');
        }

        if (! $query) {
            throw new Exception('We need a query to do a search.');
        }

        $emails = $this->mailhog->get('v2/search', ['kind' => $kind, 'query' => $query])->json();

        return $emails;
    }

}
