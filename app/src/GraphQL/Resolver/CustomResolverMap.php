<?php

namespace App\GraphQL\Resolver;

use App\Service\MutationService;
use App\Service\QueryService;
use ArrayObject as arrayObj;
use GraphQL\Type\Definition\ResolveInfo;
use Overblog\GraphQLBundle\Definition\ArgumentInterface;
use Overblog\GraphQLBundle\Resolver\ResolverMap;

class CustomResolverMap extends ResolverMap
{
    private $queryService;

    private $mutationService;

    public function __construct(QueryService $queryService, MutationService $mutationService) {
        $this->queryService = $queryService;
        $this->mutationService = $mutationService;
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
            'RootMutation' => [
                self::RESOLVE_FIELD => function (
                    $value,
                    ArgumentInterface $args,
                    arrayObj $context,
                    ResolveInfo $info
                ) {
                    switch ($info->fieldName) {
                        case 'createAuthor':
                            return $this->mutationService->createAuthor($args['author']);
                        case 'updateAuthor':
                            return $this->mutationService->updateAuthor((int)$args['id'], $args['author']);
                        case 'deleteAuthor':
                            return $this->mutationService->deleteAuthor((int)$args['id']);
                        case 'createBook':
                            return $this->mutationService->createBook($args['book']);
                        case 'updateBook':
                            return $this->mutationService->updateBook((int)$args['id'], $args['book']);
                        case 'deleteBook':
                            return $this->mutationService->deleteBook((int)$args['id']);
                        default: return null;
                    }
                },
            ],
        ];
    }
}