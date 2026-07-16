@extends('voyager::master')

@section('page_title', 'Historial de ' . $user->name)

@section('page_header')
    <div class="container-fluid">
        <h1 class="page-title">
            <i class="voyager-list"></i> Historial de Modificaciones
        </h1>
        <a href="{{ route('voyager.users.index') }}" class="btn btn-warning">
            <i class="fa-solid fa-rotate-left"></i> <span>Volver</span>
        </a>
    </div>
@stop

@section('content')
<div class="page-content container-fluid">

    {{-- Cabecera del usuario --}}
    <div class="panel panel-bordered" style="border-radius:8px">
        <div class="panel-body" style="display:flex; align-items:center; gap:16px; flex-wrap:wrap">
            <img src="{{ $user->avatar_url ?? voyager_asset('images/default.png') }}"
                 onerror="this.src='{{ voyager_asset('images/widget-backgrounds/01.jpg') }}'"
                 style="width:64px; height:64px; border-radius:50%; object-fit:cover; border:2px solid #eee">
            <div style="flex:1; min-width:200px">
                <h3 style="margin:0; font-weight:700; color:#333">{{ $user->name }}</h3>
                <div style="color:#888; font-size:13px">
                    <i class="fa fa-envelope"></i> {{ $user->email }}
                    @if((int) $user->status === 1)
                        <span class="label label-success" style="margin-left:8px">Activo</span>
                    @else
                        <span class="label label-default" style="margin-left:8px">Inactivo</span>
                    @endif
                </div>
            </div>
            <div style="text-align:right">
                <div style="font-size:28px; font-weight:700; color:#22A7F0">{{ $edits->count() }}</div>
                <div style="font-size:12px; color:#888">registros en el historial</div>
            </div>
        </div>
    </div>

    {{-- Timeline --}}
    @forelse($edits as $edit)
        @php
            $esCrear      = $edit->action === 'Usuario creado';
            $esActivar    = $edit->action === 'Usuario activado';
            $esDesactivar = $edit->action === 'Usuario desactivado';
            $esPassword   = $edit->action === 'Contraseña actualizada';
            $icono = $esCrear ? 'fa-plus' : ($esActivar ? 'fa-check' : ($esDesactivar ? 'fa-power-off' : ($esPassword ? 'fa-key' : 'fa-pencil')));
            $color = $esCrear ? '#27ae60' : ($esActivar ? '#22A7F0' : ($esDesactivar ? '#f0ad4e' : ($esPassword ? '#8e44ad' : '#6c7ae0')));
        @endphp

        <div style="display:flex; gap:14px; margin-bottom:18px">
            {{-- Punto del timeline --}}
            <div style="flex:0 0 40px; text-align:center">
                <div style="width:40px; height:40px; border-radius:50%; background:{{ $color }}; color:#fff; display:inline-flex; align-items:center; justify-content:center; box-shadow:0 2px 5px rgba(0,0,0,.2)">
                    <i class="fa {{ $icono }}"></i>
                </div>
            </div>

            {{-- Tarjeta --}}
            <div style="flex:1; background:#fff; border:1px solid #eaeaea; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,.06); overflow:hidden">
                <div style="padding:12px 16px; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:8px; border-bottom:1px solid #f2f2f2">
                    <div>
                        <div style="font-weight:700; color:#333; font-size:15px">{{ $edit->action }}</div>
                        <div style="font-size:12px; color:#999">
                            <i class="fa fa-calendar"></i>
                            {{ optional($edit->edited_at)->translatedFormat('d \d\e F \d\e Y \a \l\a\s H:i') }}
                            · {{ optional($edit->edited_at)->diffForHumans() }}
                        </div>
                    </div>
                    <div style="font-size:12px; color:#888; background:#f7f7f7; padding:6px 10px; border-radius:20px">
                        <i class="fa fa-user"></i> Modificado por <b>{{ optional($edit->editedBy)->name ?? 'Sistema' }}</b>
                    </div>
                </div>

                <div style="padding:12px 16px">
                    <div style="font-size:12px; color:#888; margin-bottom:8px">
                        @if($esCrear)
                            <i class="fa fa-info-circle"></i> Datos iniciales del usuario
                        @else
                            <i class="fa fa-info-circle"></i> Se modificó {{ count($edit->changed) }} campo(s):
                            @foreach($edit->changed as $label => $vals)
                                <span class="label" style="background:#fdf3d7; color:#8a6d3b; margin-left:2px">{{ $label }}</span>
                            @endforeach
                        @endif
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered" style="margin-bottom:0; font-size:13px">
                            <thead>
                                <tr style="background:#f7f7f7">
                                    <th style="width:25%">Campo</th>
                                    <th style="width:37%">Antes</th>
                                    <th style="width:38%">Después</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($edit->after as $label => $valorNuevo)
                                    @php $cambio = $edit->changed[$label] ?? null; @endphp
                                    <tr @if($cambio) style="background:#fdf9ec" @endif>
                                        <td style="font-weight:600; color:#555">{{ $label }}</td>
                                        <td style="color:{{ $cambio ? '#c0392b' : '#999' }}">
                                            @if($cambio)
                                                <span style="text-decoration:line-through">{{ $edit->before[$label] }}</span>
                                            @else
                                                {{ $edit->before[$label] }}
                                            @endif
                                        </td>
                                        <td style="color:{{ $cambio ? '#1a7102' : '#999' }}; font-weight:{{ $cambio ? '600' : '400' }}">
                                            @if($cambio)<i class="fa fa-arrow-right"></i> @endif{{ $valorNuevo }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center text-muted" style="padding:40px">
            <i class="fa fa-inbox fa-3x"></i>
            <p style="margin-top:10px">Este usuario no tiene modificaciones registradas.</p>
        </div>
    @endforelse

</div>
@stop
