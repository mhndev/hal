### Php Hal Object 

generating php hal object from array data


#### Sample usage:

```php

$post = [
    'title' => 'sample post title',
    'text' => 'post body goes here ...',
];

$user = [
    'username' => 'mhndev',
    'mobile' => '09124917706',
    'email' => 'majid8911303@gmail.com'
];


$comments = [
    [
        'text' => 'Hi',
        'uid'  => 12
    ],
    [
        'text' => 'OK',
        'uid'  => 14
    ],
    [
        'text' => 'NOK',
        'uid'  => 10
    ]
];

$tags = [
    'tag1', 'tag2', 'tag3'
];

$profile = [
    'avatar' => 'http://google.com/inja.jpeg',
    'name' => 'majid',
    'username' => 'mhndev',
    'bio' => 'user biography goes here ...'
];



$self_link = new \mhndev\hal\Link('self', 'http://google.com');
$next_link = new \mhndev\hal\Link('next', 'http://google.com');

$postResource = new \mhndev\hal\Resource($post);

$profileResource = new \mhndev\hal\Resource($profile);
$profileResource->addLink($self_link);


$userResource = new \mhndev\hal\Resource($user);

$userResource->addEmbeddedResource($profileResource, 'profile');

$tagsResource = new \mhndev\hal\Resource($tags);

$commentsResource = new \mhndev\hal\Resource($comments);


$postResource->addEmbeddedResource($userResource, 'user');
$postResource->addEmbeddedResource($tagsResource, 'tags');
$postResource->addEmbeddedResource($commentsResource, 'comments');

$postResource->addLink($next_link);
$postResource->addLink($self_link);

header('Content-Type: application/json');


$presenter = new \mhndev\hal\Presenter($postResource);

echo $presenter->asJson();

```