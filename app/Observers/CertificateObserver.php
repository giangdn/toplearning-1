<?php

namespace App\Observers;


use Modules\Certificate\Entities\Certificate;

class CertificateObserver extends BaseObserver
{
    /**
     * Handle the certificate "created" event.
     *
     * @param  \App\Certificate  $certificate
     * @return void
     */
    public function created(Certificate $certificate)
    {
        parent::saveHistory($certificate,'Insert','Thêm mẫu chứng chỉ');
    }

    /**
     * Handle the certificate "updated" event.
     *
     * @param  \App\Certificate  $certificate
     * @return void
     */
    public function updated(Certificate $certificate)
    {
        parent::saveHistory($certificate,'Update','Cập nhật mẫu chứng chỉ');
    }

    /**
     * Handle the certificate "deleted" event.
     *
     * @param  \App\Certificate  $certificate
     * @return void
     */
    public function deleted(Certificate $certificate)
    {
        parent::saveHistory($certificate,'Delete','Xóa mẫu chứng chỉ');
    }

    /**
     * Handle the certificate "restored" event.
     *
     * @param  \App\Certificate  $certificate
     * @return void
     */
    public function restored(Certificate $certificate)
    {
        //
    }

    /**
     * Handle the certificate "force deleted" event.
     *
     * @param  \App\Certificate  $certificate
     * @return void
     */
    public function forceDeleted(Certificate $certificate)
    {
        //
    }
}
