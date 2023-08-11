<?php

namespace App\GraphQL\Resolver;

use App\Service\QueryService;
use ArrayObject as arrayObj;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class CustomResolverMap extends ResolverMap
{
    private $queryService;

    public function __construct(QueryService $queryService) {
        $this->queryService = $queryService;
    }

    /**
     * @inheritDoc
     */
    protected function map(): array
    {
        return [
            'RootQuery' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    arrayObj $context,
                    ResolveInfo $info
                ) {
                    switch ($info->fieldName) {
                        case 'author':
                            return $this->queryService->getAuthor($args['id']);
                        case 'authors':
                            return $this->queryService->getAllAuthors();
                        case 'book':
                            return $this->queryService->getBook($args['id']);
                        case 'books':
                            return $this->queryService->getAllBooks();
                        default: return null;
                    }
                },
            ],
        ];
    }
}