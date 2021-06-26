<?php

namespace App\Repository;

use Illuminate\Support\Collection;

interface ConfirmInterface
{
    public function all($id);
    public function view($id,$isAccepted);
    public function confirm($id, $value);
    public function reject($id, $value);
    public function singleReject($id, $value);
    public function search($store,$id);
    public function saveToCommentTable(array $data);
    public function singleProductConfirmation($id);
    public function getProductRefID($id);
    public function updateRejectProduct($id);
    public function updateReceivedProductIsaccepted($id);
}