<div class="row">
    <div class="col col-12 col-md-12 col-sm-12 col-xs-12">
        <div class="table-responsive">
            <table id="detail-table" class="table table-sm text-center" style="border-collapse: collapse; border-spacing: 0; width: 100%; font-size: 0.8rem;">
                <thead>
                    <tr>
                        <th>No. </th>
                        <th>Role</th>
                        <th>Create</th>
                        <th>Read</th>
                        <th>Update</th>
                        <th>Delete</th>
                        <th>Reset</th>
                        <th>Reject</th>
                        <th>Close</th>
                        <th>Admin</th>
                        <th>Approve Lvl</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($menuparam['itemdata']) && !empty($menuparam['itemdata']) && isset($menuparam['itemdata']['menu_role']) && !empty($menuparam['itemdata']['menu_role']))
                        @foreach($menuparam['itemdata']['menu_role'] as $detail)
                            <tr>
                                <td></td>
                                <td>
                                    {{ isset($detail['role']) && !empty($detail['role']) ? $detail['role']['name'] : '' }}
                                    <input type="hidden" class="iddetail" name="iddetail[]" value="{{ $detail['id'] }}">
                                    <input type="hidden" class="idrole" name="idrole[]" value="{{ $detail['idrole'] }}">
                                </td>
                                <td>
                                    <select required name="is_create[]" class="form-control form-control-sm action-role is_create">
                                        <option {{ $detail['is_create'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_create'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_read[]" class="form-control form-control-sm action-role is_read">
                                        <option {{ $detail['is_read'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_read'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_update[]" class="form-control form-control-sm action-role is_update">
                                        <option {{ $detail['is_update'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_update'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_delete[]" class="form-control form-control-sm action-role is_delete">
                                        <option {{ $detail['is_delete'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_delete'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_reset[]" class="form-control form-control-sm action-role is_reset">
                                        <option {{ $detail['is_reset'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_reset'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_reject[]" class="form-control form-control-sm action-role is_reject">
                                        <option {{ $detail['is_reject'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_reject'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_close[]" class="form-control form-control-sm action-role is_close">
                                        <option {{ $detail['is_close'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_close'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <select required name="is_admin[]" class="form-control form-control-sm action-role" onchange="Menu.setroleaction(this,event);" >
                                        <option {{ $detail['is_admin'] == 0 ? 'selected' : '' }} value="0">Tidak</option>
                                        <option {{ $detail['is_admin'] == 1 ? 'selected' : '' }} value="1">Ya</option>
                                    </select>
                                </td>
                                <td>
                                    <input class="form-control form-control-sm" name="tingkat_approve_role[]" class="tingkat_approve_role" type="text" onchange="Main.inputnumberonly(this,event);" value="{{$detail['tingkatapprove']}}">
                                </td>
                                <td>
                                    &nbsp
                                        <button type="button" onclick="{{ $menuparam['mainjsroute'] }}.deleterow(this, event)" class="btn btn-danger btn-sm">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    &nbsp
                                </td>
                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>