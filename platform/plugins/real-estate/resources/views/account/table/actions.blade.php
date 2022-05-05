<div class="table-actions">
    @if (!empty($edit))
        <a href="{{ route($edit, $item->id) }}" class="btn btn-icon btn-sm btn-primary" data-bs-toggle="tooltip" data-bs-original-title="{{ trans('core/base::tables.edit') }}"><i class="fa fa-edit"></i></a>
    @endif

    @if (!empty($delete))
        <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog" data-section="{{ route($delete, $item->id) }}" role="button" data-bs-toggle="tooltip" data-bs-original-title="{{ trans('core/base::tables.delete_entry') }}" >
            <i class="fa fa-trash"></i>
        </a>
    @endif

    <a href="#" class="btn btn-icon btn-sm btn-info button-renew" data-section="{{ route('public.account.properties.renew', $item->id) }}" data-url="{{ route('public.account.properties.get', $item->id) }}" role="button" data-bs-toggle="tooltip" data-bs-original-title="{{ trans('core/base::tables.renew') }}" >
        <i class="fas fa-sync-alt"></i>
    </a>
</div>
