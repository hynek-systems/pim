<?php

namespace Hynek\Pim\Products;

use App\Models\EntityField;
use App\QueryBuilder\Filters\EntityFieldValueFilter;
use Hynek\Pim\Services\ProductService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CreateQueryBuilder
{
    public function __construct(protected ?string $defaultSort = null)
    {
    }

    public function __invoke(?Builder $query = null): QueryBuilder|Builder
    {
        if (is_null($query)) {
            $query = current_site()?->products();
        }

        return new QueryBuilder($query)
            ->with(['entityFieldValues'])
            ->join('entity_field_values', 'entity_field_values.entity_id', 'entities.id')
            ->allowedFilters([
                'id',
                'title',
                'uri',
                'created_at',
                'updated_at',
                ...$this->getEntityFieldValuesFilters()
            ])
            ->allowedSorts([
                'id',
                'title',
                'uri',
                'created_at',
                'updated_at',
            ])
            ->defaultSort($this->defaultSort);
    }

    protected function getEntityFieldValuesFilters()
    {
        return $this->getEntityFields()->mapWithKeys(
            fn(EntityField $field) => AllowedFilter::custom($field->name, new EntityFieldValueFilter())
        );
    }

    protected function getEntityFields(): Collection
    {
        return EntityField::query()
            ->where('entity_type_id', ProductService::get_entity_type()->id)
            ->get();
    }
}
