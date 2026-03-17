<?php

namespace EdgeBox\SyncCore\V2\Configuration;

use EdgeBox\SyncCore\Interfaces\Configuration\IDefineTextProfileHtmlPolicy;
use EdgeBox\SyncCore\V2\Raw\Model\HtmlPolicy;

class DefineTextProfileHtmlPolicy implements IDefineTextProfileHtmlPolicy
{
    /**
     * @var HtmlPolicy
     */
    protected $dto;

    public function __construct(HtmlPolicy $dto)
    {
        $this->dto = $dto;
    }

    public function setDialect($set)
    {
        $this->dto->setDialect($set);

        return $this;
    }

    public function setRequireWellFormedHtml(bool $set)
    {
        $this->dto->setRequireWellFormedHtml($set);

        return $this;
    }

    public function allowTag(string $tag)
    {
        $tags = $this->dto->getAllowedTags() ?? [];
        if (!in_array($tag, $tags, true)) {
            $tags[] = $tag;
            $this->dto->setAllowedTags($tags);
        }

        return $this;
    }

    public function allowAttribute(string $tag, string $attribute, ?array $allowed_values = null)
    {
        $this->upsertAttributeRule($tag, $attribute, $allowed_values, null);

        return $this;
    }

    public function allowAttributePattern(string $tag, string $attribute, string $allowed_pattern)
    {
        $this->upsertAttributeRule($tag, $attribute, null, $allowed_pattern);

        return $this;
    }

    public function setAllowStyleAttribute(bool $set)
    {
        $this->dto->setAllowStyleAttribute($set);

        return $this;
    }

    /**
     * @param null|string[] $allowed_values
     */
    protected function upsertAttributeRule(string $tag, string $attribute, ?array $allowed_values, ?string $allowed_pattern): void
    {
        $attributes = $this->dto->getAllowedAttributes() ?? [];
        $rule_index = null;

        foreach ($attributes as $index => $existing_attribute) {
            if (($existing_attribute['tag'] ?? null) === $tag && ($existing_attribute['attribute'] ?? null) === $attribute) {
                $rule_index = $index;

                break;
            }
        }

        if (null === $rule_index) {
            $rule = [
                'tag' => $tag,
                'attribute' => $attribute,
            ];
        } else {
            $rule = $attributes[$rule_index];
        }

        if (null !== $allowed_values) {
            $existing_values = $rule['allowedValues'] ?? [];
            $rule['allowedValues'] = array_values(array_unique(array_merge($existing_values, $allowed_values)));
            unset($rule['allowedPattern']);
        }

        if (null !== $allowed_pattern) {
            $rule['allowedPattern'] = $allowed_pattern;
            unset($rule['allowedValues']);
        }

        if (null === $rule_index) {
            $attributes[] = $rule;
        } else {
            $attributes[$rule_index] = $rule;
        }

        $this->dto->setAllowedAttributes($attributes);
    }
}
