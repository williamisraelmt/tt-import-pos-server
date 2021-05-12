<?php


namespace App\Console\Commands\Models;


class OdooPaymentInvoice implements IOdooModel
{
    /**
     * @var int
     */
    private $invoiceId;
    /**
     * @var int
     */
    private $paymentId;

    /**
     * OdooPaymentInvoice constructor.
     * @param int $invoiceId
     * @param int $paymentId
     */
    public function __construct(int $invoiceId, int $paymentId)
    {
        $this->invoiceId = $invoiceId;
        $this->paymentId = $paymentId;
    }

    public function toStoreAsArray(): array
    {
        return [
            "invoice_id" => $this->invoiceId,
            "payment_id" => $this->paymentId
        ];
    }

    /**
     * @return int
     */
    public function getInvoiceId(): int
    {
        return $this->invoiceId;
    }

    /**
     * @param int $invoiceId
     */
    public function setInvoiceId(int $invoiceId): void
    {
        $this->invoiceId = $invoiceId;
    }

    /**
     * @return int
     */
    public function getPaymentId(): int
    {
        return $this->paymentId;
    }

    /**
     * @param int $paymentId
     */
    public function setPaymentId(int $paymentId): void
    {
        $this->paymentId = $paymentId;
    }
}
