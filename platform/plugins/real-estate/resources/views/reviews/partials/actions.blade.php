<div class="table-actions">
    <a href="#" class="btn btn-icon btn-sm btn-danger deleteDialog"
       data-section="{{ route('reviews.destroy', $item->id) }}" role="button"
       data-bs-toggle="tooltip" data-bs-original-title="{{ trans('core/base::tables.delete_entry') }}">
        <i class="fa fa-trash"></i>
    </a>
</div>
