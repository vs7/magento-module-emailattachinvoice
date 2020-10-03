<?php

class VS7_EmailAttachInvoice_Model_Observer
{
    public function beforeSendOrder($observer)
    {
        $update = $observer->getEvent()->getUpdate();
        $mailTemplate = $observer->getEvent()->getTemplate();
        $helper = Mage::helper('emailattachments');
        if ($update) {
            return;
        }

        $order = $observer->getObject();
        if ($order && $order->hasInvoices()) {
            $invoices = $order->getInvoiceCollection();
            foreach ($invoices as $invoice) {
                $invoicePdf = Mage::getModel('sales/order_pdf_invoice')->getPdf(array($invoice));
                $helper->addAttachment(
                    $invoicePdf, $mailTemplate, $helper->getInvoiceAttachmentName($invoice)
                );
            }
        }
    }
}