<?php

namespace App\Modules\Appointment\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Modules\Appointment\Http\Requests\Admin\AppointmentTypeRequest;
use App\Modules\Appointment\Repositories\AppointmentTypeRepository;
use Illuminate\Http\Request;

class AppointmentTypeController extends Controller
{
    /**
     * The AppointmentTypeRepository implementation.
     *
     * @var AppointmentTypeRepository
     */
    protected $appointmentType;

    /**
     * Create a new controller instance.
     *
     * @param AppointmentTypeRepository $appointmentType
     */
    public function __construct(
        AppointmentTypeRepository $appointmentType
    )
    {
        $this->appointmentType = $appointmentType;
        $this->middleware('permission:appointment-view,admin', ['only' => ['index']]);
        $this->middleware('permission:appointment-create,admin', ['only' => ['create', 'store']]);
        $this->middleware('permission:appointment-edit,admin', ['only' => ['edit', 'update']]);
        $this->middleware('permission:appointment-delete,admin', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Appointment Type';
        $types = $this->appointmentType->allWith([]);
        return view('appointment::admin.appointment.types.index', compact('types', 'title'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Create Appointment Type';
        return view('appointment::admin.appointment.types.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(AppointmentTypeRequest $request)
    {
        $inputs = $request->validated();
        $inputs['created_by'] = auth()->id();
        $type = $this->appointmentType->create($inputs);
        if ($request->hasFile('icon')) {
            $type->addMediaFromRequest('icon')->toMediaCollection('appointmentType');
        }
        return redirect()->route('admin.appointment.types.index')->with('flash_success', 'Appointment Type ' . $type->name . ' added!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $type = $this->appointmentType->findOrFail($id);
        return view('appointment::admin.appointment.types.edit', compact('type'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(AppointmentTypeRequest $request, $id)
    {
        $type = $this->appointmentType->findOrFail($id);
        $inputs = $request->validated();
        $inputs['created_by'] = auth()->id();
        $type = $this->appointmentType->update($type->id, $inputs);
        if ($request->hasFile('icon')) {
            $mediaItems = $type->getMedia('appointmentType');
            if (isset($mediaItems) && isset($mediaItems[0])) {
                $mediaItems[0]->delete();
            }
            $type->addMediaFromRequest('icon')->toMediaCollection('appointmentType');
        }
        return redirect()->route('admin.appointment.types.index')->with('flash_success', 'Appointment Type ' . $type->name . ' updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = $this->appointmentType->findOrFail($id);
        $this->appointmentType->destroy($type->id);
        $message = 'Appointment Type deleted successfully';
        return response()->json(['status' => 'ok', 'message' => $message], 200);
    }

    /**
     * Change status the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $id = $request->get('id');
        $type = $this->appointmentType->findOrFail($id);
        if ($type->is_active == 0) {
            $status = 1;
            $message = 'Appointment Type with name "' . $type->name . '" is published.';
        } else {
            $status = 0;
            $message = 'Appointment Type with name "' . $type->name . '" is unpublished.';
        }
        $this->appointmentType->update($type->id, ['is_active' => $status]);
        $type = $this->appointmentType->find($request->get('id'));

        return response()->json(['status' => 'ok', 'message' => $message, 'response' => $type], 200);
    }

    public function sortOrder(Request $request)
    {
        $sorts = explode('&', str_replace('order[]=', '', $request->orderings));
        $this->appointmentType->model()->setNewOrder($sorts);
        return response()->json(['status' => 'success', 'message' => 'Appointment type sorting updated successfully.'], 200);
    }
}
