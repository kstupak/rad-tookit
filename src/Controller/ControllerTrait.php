<?php

/*
 * This file is part of "rad-tookit".
 *
 * (c) Kostiantyn Stupak <konstantin.stupak@gimmemore.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace KStupak\RAD\Controller;

use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait ControllerTrait
{
    private SerializerInterface $serializer;

    private function getResponse($data, int $code = Response::HTTP_OK, ?array $groups = [], ?array $headers = []): Response
    {
        $headers = \array_merge($headers, [
            'Content-Type' => 'application/json;charset=UTF-8'
        ]);

        $context = new SerializationContext();
        $context->setSerializeNull(true);

        if (!empty($groups)) {
            $context->setGroups($groups);
        }

        $data = $this->serializer->serialize($data, 'json', $context);
        return new Response($data, $code, $headers);
    }

    private function extract(Request $request, string $className): object
    {
        return $this->serializer->deserialize($request->getContent(), $className, 'json');
    }

    /** @return array|string|int|float|bool */
    private function getRequestContent(Request $request)
    {
        $rawContent = $request->getContent();
        $decoded = \json_decode($rawContent, true);

        return $decoded ?? $rawContent;
    }

    /** @return array|string|int|float|bool|null */
    private function getJsonRequestField(Request $request, string $field, $default = null)
    {
        $content = $this->getRequestContent($request);
        return \array_key_exists($field, $content) ? $content[$field] : $default;
    }

    private function getListResponse($data): Response
    {
        return $this->getResponse($data, Response::HTTP_OK, ['list']);
    }

    private function getViewResponse($data): Response
    {
        return $this->getResponse($data, Response::HTTP_OK, ['view']);
    }

    private function getCreatedResponse($data): Response
    {
        return $this->getResponse($data, Response::HTTP_CREATED, ['view']);
    }
}
