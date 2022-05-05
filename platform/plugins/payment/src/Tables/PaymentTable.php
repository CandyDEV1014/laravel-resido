<?php

namespace Botble\Payment\Tables;

use BaseHelper;
use Botble\Payment\Enums\PaymentStatusEnum;
use Botble\Payment\Repositories\Interfaces\PaymentInterface;
use Botble\Table\Abstracts\TableAbstract;
use Html;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Botble\RealEstate\Models\Account;

class PaymentTable extends TableAbstract
{

    /**
     * @var bool
     */
    protected $hasActions = true;

    /**
     * @var bool
     */
    protected $hasFilter = true;

    /**
     * PaymentTable constructor.
     * @param DataTables $table
     * @param UrlGenerator $urlGenerator
     * @param PaymentInterface $paymentRepository
     */
    public function __construct(DataTables $table, UrlGenerator $urlGenerator, PaymentInterface $paymentRepository)
    {
        parent::__construct($table, $urlGenerator);

        $this->repository = $paymentRepository;

        if (!Auth::user()->hasAnyPermission(['payment.show', 'payment.destroy'])) {
            $this->hasOperations = false;
            $this->hasActions = false;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function ajax()
    {
        $data = $this->table
            ->eloquent($this->query())
            ->editColumn('charge_id', function ($item) {
                return Html::link(route('payment.show', $item->id), $item->charge_id);
            })
            ->editColumn('agent', function ($item) {
                return $item->first_name .' '. $item->last_name;
            })
            ->editColumn('email', function ($item) {
                return $item->email;
            })
            ->editColumn('checkbox', function ($item) {
                return $this->getCheckbox($item->id);
            })
            ->editColumn('payment_channel', function ($item) {
                return $item->payment_channel->label();
            })
            ->editColumn('amount', function ($item) {
                return $item->amount . ' ' . $item->currency;
            })
            ->editColumn('created_at', function ($item) {
                return BaseHelper::formatDate($item->created_at);
            })
            ->editColumn('status', function ($item) {
                return $item->status->toHtml();
            })
            ->addColumn('operations', function ($item) {
                return $this->getOperations('payment.show', 'payment.destroy', $item);
            });

        return $this->toJson($data);
    }

    /**
     * {@inheritDoc}
     */
    public function query()
    {
        $query = $this->repository->getModel()
        ->join('re_accounts', 're_accounts.id', '=', 'payments.customer_id')
        ->select([
            'payments.id',
            'payments.charge_id',
            'payments.customer_id',
            'payments.amount',
            'payments.currency',
            'payments.payment_channel',
            'payments.created_at',
            'payments.status',
            'payments.order_id',
            're_accounts.first_name',
            're_accounts.last_name',
            're_accounts.email',
        ])
        ->with('customer');

        return $this->applyScopes($query);
    }

    /**
     * {@inheritDoc}
     */
    public function columns()
    {
        return [
            'id'              => [
                'title' => trans('core/base::tables.id'),
                'width' => '20px',
            ],
            'charge_id'       => [
                'title' => trans('plugins/payment::payment.charge_id'),
                'class' => 'text-start',
            ],
            'agent'       => [
                'name'  => 're_accounts.first_name',
                'title' => trans('plugins/payment::payment.agent'),
                'class' => 'text-start',
            ],
            'email'       => [
                'name'  => 're_accounts.email',
                'title' => trans('plugins/payment::payment.email'),
                'class' => 'text-start',
            ],
            'amount'          => [
                'title' => trans('plugins/payment::payment.amount'),
                'class' => 'text-start',
            ],
            'payment_channel' => [
                'title' => trans('plugins/payment::payment.payment_channel'),
                'class' => 'text-start',
            ],
            'created_at'      => [
                'title' => trans('core/base::tables.created_at'),
                'width' => '100px',
            ],
            'status'          => [
                'title' => trans('core/base::tables.status'),
                'width' => '100px',
            ],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function bulkActions(): array
    {
        return $this->addDeleteAction(route('payment.deletes'), 'payment.destroy', parent::bulkActions());
    }

    /**
     * {@inheritDoc}
     */
    public function getBulkChanges(): array
    {
        return [
            'status'     => [
                'title'    => trans('core/base::tables.status'),
                'type'     => 'customSelect',
                'choices'  => PaymentStatusEnum::labels(),
                'validate' => 'required|in:' . implode(',', PaymentStatusEnum::values()),
            ],
            'charge_id'  => [
                'title'    => trans('plugins/payment::payment.charge_id'),
                'type'     => 'text',
                'validate' => 'required|max:120',
            ],
            'created_at' => [
                'title' => trans('core/base::tables.created_at'),
                'type'  => 'date',
            ],
        ];
    }
}
