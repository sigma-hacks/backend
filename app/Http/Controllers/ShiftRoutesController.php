<?php

namespace App\Http\Controllers;

use App\Models\ShiftRoute;
use Illuminate\Http\Request;

class ShiftRoutesController extends CRUDBaseController
{
    public $model = ShiftRoute::class;
}
