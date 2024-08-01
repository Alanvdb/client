# Client

A basic PHP client.

## Installation

Pour installer ce client PHP, utilisez Composer. Exécutez la commande suivante dans votre terminal :

```sh
composer require alanvdb/client
```

## Utilisation

Voici un exemple de base pour utiliser le client :

```php
<?php

require 'vendor/autoload.php';

use AlanVdb\Client\Client;
use Psr\Http\Message\RequestInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\ResponseFactory;

$responseFactory = new ResponseFactory();
$client = new Client($responseFactory, '/path/to/your/cert.pem');

$request = new Request('GET', 'https://api.example.com/data');
$response = $client->sendRequest($request);

echo $response->getBody();
```

## Tests

Pour exécuter les tests, utilisez PHPUnit. Assurez-vous d'avoir PHPUnit installé et exécutez la commande suivante :

```sh
vendor/bin/phpunit
```

## Contribution

Les contributions sont les bienvenues ! Veuillez suivre ces étapes pour contribuer :

1. Fork le projet
2. Créez une branche pour votre fonctionnalité (`git checkout -b feature/AmazingFeature`)
3. Commitez vos modifications (`git commit -m 'Add some AmazingFeature'`)
4. Poussez vers la branche (`git push origin feature/AmazingFeature`)
5. Ouvrez une Pull Request

Assurez-vous que votre code suit les conventions de codage PSR et que toutes les nouvelles fonctionnalités sont couvertes par des tests.

## Licence

Ce projet est sous licence MIT. Voir le fichier [LICENSE](LICENSE) pour plus de détails.