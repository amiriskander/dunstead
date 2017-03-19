<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Unirest;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/test/", name="default_test")
     */
    public function testAction()
    {
        return $this->render('default/test.html.twig');
    }

    /**
     * @Route("/test/one-signal/", name="default_test_onesignal")
     */
    public function testOneSignal()
    {
        /*
        // search Songs of Frank Sinatra
        $headers = array('Accept' => 'application/json');
        $query = array('q' => 'Frank sinatra', 'type' => 'track');

        $response = Unirest\Request::get('https://api.spotify.com/v1/search',$headers,$query);
        // or use a plain text request
        // $response = Unirest\Request::get('https://api.spotify.com/v1/search?q=Frank%20sinatra&type=track');

        // Display the result
        dump($response->body);
        */

        $messages = array();

        $content = array(
            "en" => 'English Message'
        );

        $fields = array(
            'app_id' => "36f24ffe-6e7b-4c82-9011-96ba12197bfb",
            'included_segments' => array('All'),
            'data' => array("foo" => "bar"),
            'contents' => $content
        );

        $fields = json_encode($fields);

        $messages[] = "\nJSON sent:\n";
        $messages[] = $fields;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic NzQ0NjUzYzItMzZiNi00YmEzLThkZTctYzgxOGZiZjM3N2Yw'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $return["allresponses"] = $response;
        $return = json_encode( $return);

        $messages[] = "\n\nJSON received:\n";
        $messages[] = $return;

        return $this->render('default/test_onesignal.html.twig', array(
            'messages' => $messages,
        ));
    }
}
