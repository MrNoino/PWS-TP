<?php

require_once ('./vendor/autoload.php');
require_once ('./lib/api_key.php');

use Tmdb\Client;
use Tmdb\Token\Api\BearerToken;
use Tmdb\Event\BeforeRequestEvent;
use Tmdb\Event\Listener\Request\AcceptJsonRequestListener;
use Tmdb\Event\Listener\Request\ApiTokenRequestListener;
use Tmdb\Event\Listener\Request\ContentTypeJsonRequestListener;
use Tmdb\Event\Listener\Request\UserAgentRequestListener;
use Tmdb\Event\Listener\Request\LanguageFilterRequestListener;
use Tmdb\Event\Listener\RequestListener;
use Tmdb\Event\RequestEvent;

//en-US
function setup_api_client($language = "pt-PT"){

    $token = new BearerToken(TMDB_BEARER_TOKEN);

    $ed = new Symfony\Component\EventDispatcher\EventDispatcher();

    $client = new Client([
        'api_token' => $token,
        'secure' => false,
        'event_dispatcher' => [
            'adapter' => $ed
        ],
        'http' => [
            'client' => null,
            'request_factory' => null,
            'response_factory' => null,
            'stream_factory' => null,
            'uri_factory' =>null
        ]
        ]);

    $requestListener = new RequestListener($client->getHttpClient(), $ed);
    $ed->addListener(RequestEvent::class, $requestListener);

    $apiTokenListener = new ApiTokenRequestListener($client->getToken());
    $ed->addListener(BeforeRequestEvent::class, $apiTokenListener);

    $acceptJsonListener = new AcceptJsonRequestListener();
    $ed->addListener(BeforeRequestEvent::class, $acceptJsonListener);

    $jsonContentTypeListener = new ContentTypeJsonRequestListener();
    $ed->addListener(BeforeRequestEvent::class, $jsonContentTypeListener);

    $userAgentListener = new UserAgentRequestListener();
    $ed->addListener(BeforeRequestEvent::class, $userAgentListener);

    $languageFilterListener = new LanguageFilterRequestListener($language);
    $ed->addListener(BeforeRequestEvent::class, $languageFilterListener);

    return $client;

}


?>