<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\AddressUser;
use Validator;

class AddressController extends BaseController
{
    public function addAddress(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'street' => 'required',
            'pin_code' => 'required',
            'location' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $address = new Address();
        $address->name = $request->get('name');
        $address->email = $request->get('email');
        $address->address = $request->get('address');
        $address->street = $request->get('street');
        $address->pin_code = $request->get('pin_code');
        $address->location = $request->get('location');
        $address->save();

        $addressUser = new AddressUser();
        $addressUser->address_id = $address->id;
        $addressUser->user_id = $request->get('user_id');
        $addressUser->save();

        return $this->sendResponse($address->toArray(), 'Dirección agregada.');
    }

    public function getAddress($id)
    {
        $address = AddressUser::with('address')->where('user_id', $id)->get(['id', 'address_id']);
        return $this->sendResponse($address->toArray(), 'Dirección obtenidas.');
    }

    public function editAddress($id, Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|email',
            'address' => 'required',
            'street' => 'required',
            'pin_code' => 'required',
            'location' => 'required',
            'user_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $address = Address::findOrFail($id);
        $address->update($request->all());

        return $this->sendResponse($address->toArray(), 'Dirección actualizada');
    }

    public function deleteAddress($id)
    {
        $addressUser = AddressUser::findOrFail($id);
        $addressUser->delete();
        $address = Address::findOrFail($addressUser->address_id);
        $address->delete();
        return $this->sendResponse($address->toArray(), 'Dirección eliminada.');
    }

}
