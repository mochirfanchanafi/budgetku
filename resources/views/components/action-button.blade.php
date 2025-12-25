<div id="action-button">
    <div class="row" id="action-row">
        <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button">
            <a href="{{ url('/') }}?page={{ $menuparam['menu'] }}" class="btn btn-block btn-sm bg-secondary text-light" style="margin-top:5px;"><i class="fa-solid fa-arrow-rotate-left"></i> Batal</a>
        </div>
        @if(isset($menuparam['menu_action']) && !empty($menuparam['menu_action']))
            <div class="col col-12 col-md-{{ 10 - ( $menuparam['menu_action']['actioncount'] * 2 ) }} col-sm-12 col-xs-12"></div>
            @if($menuparam['menu_action']['iscreate'] == '1')
                <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button text-end">
                    <input type="hidden" name="submit" value="save">
                    <button type="submit" class="btn btn-block bg-info btn-sm text-light" style="margin-top:5px;"><i class="fa-solid fa-floppy-disk"></i> Simpan</button>   
                </div>
            @endif
            @if($menuparam['menu_action']['isupdate'] == '1')
                <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button text-end">
                    <input type="hidden" name="submit" value="save">
                    <button type="submit" class="btn btn-block bg-info btn-sm text-light" style="margin-top:5px;"><i class="fa-solid fa-pencil"></i> Ubah</button>   
                </div>
            @endif
            @if($menuparam['menu_action']['isreset'] == '1')
                <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button text-end">
                    <button onclick="{{$menuparam['mainjsroute']}}.reset(this,event);" style="margin-top:5px;" class="btn btn-block bg-warning text-light btn-sm text-light"><i class="fa-solid fa-arrow-rotate-left"></i> Reset Draft</button>
                </div>
            @endif
            @if($menuparam['menu_action']['isreject'] == '1')
                <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button text-end">
                    <button onclick="{{$menuparam['mainjsroute']}}.reject(this,event);" style="margin-top:5px;" class="btn btn-block bg-danger text-light btn-sm text-light"><i class="fa-solid fa-xmark"></i> Reject</button>
                </div>
            @endif
            @if($menuparam['menu_action']['isclose'] == '1')
                <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button text-end">
                    <button onclick="{{$menuparam['mainjsroute']}}.close(this,event);" style="margin-top:5px;" class="btn btn-block bg-secondary text-light btn-sm text-light"><i class="fa-solid fa-ban"></i> Close</button>
                </div>
            @endif
            @if($menuparam['menu_action']['isapprove'] == '1')
                <div class="col col-12 col-md-2 col-sm-12 col-xs-12 col-button text-end">
                    <button onclick="{{$menuparam['mainjsroute']}}.approve(this,event);" style="margin-top:5px;" class="btn btn-block bg-info text-light btn-sm text-light"><i class="fa-solid fa-check"></i> Approve</button>
                </div>
            @endif
        @endif
    </div>
</div>