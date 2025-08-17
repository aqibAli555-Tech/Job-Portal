<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\Request;
use App\Models\Currency;

class CurrencyController extends AdminBaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(Request $request)
    {
        $currency = Currency::get_Currency($request);
        return view('vendor.admin.currency.index', compact('currency'));
    }

    public function add()
    {
        return view('vendor.admin.currency.create');
    }

    public function currency_edit($id)
    {
        $currency = Currency::find($id);
        return view('vendor.admin.currency.edit', compact('currency'));
    }

    public function add_currency(Request $request)
    {
        $currency = new Currency();
        $currency->name = $request->input('name');
        $currency->code = $request->input('code');
        $currency->symbol = $request->input('symbol');
        $currency->html_entities = $request->input('html_entities');
        $currency->in_left = $request->input('in_left');
        $currency->decimal_places = $request->input('decimal_places');
        $currency->decimal_separator = $request->input('decimal_separator');
        $currency->thousand_separator = $request->input('thousand_separator');

        if ($currency->save()) {
            flash('Currency Added Successfully')->info();
            return redirect(admin_url('currencies'));
        } else {
            flash('Please Trey Agian')->info();
            return redirect()->back();
        }
    }

    public function update_currency(Request $request)
    {
        $currency = Currency::find($request->input('id'));
        $currency->name = $request->input('name');
        $currency->code = $request->input('code');
        $currency->symbol = $request->input('symbol');
        $currency->html_entities = $request->input('html_entities');
        $currency->in_left = $request->input('in_left');
        $currency->decimal_places = $request->input('decimal_places');
        $currency->decimal_separator = $request->input('decimal_separator');
        $currency->thousand_separator = $request->input('thousand_separator');
        if ($currency->save()) {
            flash('Updated Successfully')->info();
            return redirect(admin_url('currencies'));
        } else {
            flash('Please Trey Agian')->info();
            return redirect(admin_url('currency_edit  /' . $request->input('id')));
        }
    }

    public function delete_currency($id)
    {
        $currency = Currency::find($id);
        if ($currency->delete()) {
            flash('Updated Successfully')->info();
            return redirect()->back();
        } else {
            flash('Please Trey Agian')->info();
            return redirect()->back();
        }

    }
}

?>