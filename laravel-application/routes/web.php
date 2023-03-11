<?php

use App\Http\Controllers\Admin\Alumnos\AlumnosController;
use App\Http\Controllers\Admin\Alumnos\AlumnosEntradasController;
use App\Http\Controllers\Admin\Solicitudes\SolicitudesController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Alumno\AlumnoPerfil\PerfilController;
use App\Http\Controllers\Alumno\CargaHoras\EntradaController;
use App\Http\Controllers\Alumno\Progreso\ProgresoController;
use App\Http\Controllers\Alumno\Registro\RegistroController;
use App\Http\Controllers\Alumno\AssessorsController;

use App\Http\Controllers\ErrorController;
use Illuminate\Support\Facades\Route;


// ============ ASSESSORS ROUTES ============
//AssessorsController
Route::get('/' , [AssessorsController::class, 'Index'])->name('assessors')->middleware('auth.device');
Route::get('/reloj', [AssessorsController::class, 'Clock'])->name('clock')->middleware('auth.device');

//RegistroController
Route::get('/registro', [RegistroController::class, 'Register'])->name('assessors.register');
Route::get('/registro/dispositivo', [RegistroController::class, 'AddDevice'])->name('assessors.register.device');
Route::post('/signin-device', [RegistroController::class, 'SignIn'])->name('assessors.signin')->middleware('transaction');

//PerfilController
Route::get("/profile",[PerfilController::class,'Profile'])->name('profile');//->middleware('auth.device');
Route::post("/profile",[PerfilController::class,'UpdateProfilePicture'])->name('profile.update.profile.picture');

//ProgresoController
Route::get('/progreso_horas/cant/{elements}/pag/{page}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [ProgresoController::class, 'MyHours'])->name('hours_progress');//->middleware('auth.device');
Route::get('/progreso_general/cant/{elements}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [ProgresoController::class, 'MyProgress'])->name('general_progress');//->middleware('auth.device');

//EntradaController
Route::post('/entrada/registrar-hora-entrada', [EntradaController::class, 'RegistarHoraEntrada'])->name('entrada.registar-hora-entrada')->middleware('transaction');
Route::put('/entrada/registrar-hora-salida', [EntradaController::class, 'RegistarHoraSalida'])->name('entrada.registar-hora-salida')->middleware('transaction');
Route::get('/entrada/obtener-entrada-diaria', [EntradaController::class, 'ObtenerEntradaDiaria'])->name('entrada.obtener-entrada-diaria');


// ============ ADMIN ROUTES ============

// !DEPRECATED
Route::post('/admin/set-id-ciclo-escolar', [AdminController::class, 'SetIdCicloEscolarSession']);

// !DEPRECATED
Route::get('/admin/get-id-ciclo-escolar', [AdminController::class, 'GetIdCicloEscolarSession']);

//AdminController
Route::get('/admin', [AdminController::class, 'Index'])->name('admin');
Route::get('/admin/login', [AdminController::class, 'Login'])->name('admin.login');
Route::post('/admin/signin', [AdminController::class, 'SignIn'])->name('admin.signin');
Route::post('/admin/logout', [AdminController::class, 'Logout'])->name('admin.logout');
Route::get('/admin/ciclo-escolar', [AdminController::class, 'GetCicloEscolar'])->name('admin.school.cycles')->middleware('admin.auth');
Route::delete('/admin/delete_old_data', [AdminController::class, 'DeleteOldData'])->middleware('admin.auth');

//SolicitudesController
// Se pasa la cantidad de elementos por pagina y la pagina en cuestion que se necesita
Route::get('/admin/solicitudes/dispositivos/cant/{elements}/pag/{page}/selected_school_cycle/{selected_school_cycle}', [SolicitudesController::class, 'DeviceRequests'])->name('admin.requests.devices')->middleware('admin.auth');
Route::post('/admin/solicitudes/dispositivos', [SolicitudesController::class, 'AcceptRequest'])->name('admin.requests.devices.accept')->middleware('admin.auth')->middleware('transaction');
Route::delete('/admin/solicitudes/dispositivos', [SolicitudesController::class, 'RejectRequest'])->name('admin.requests.device.reject')->middleware('admin.auth');

//AlumnosController
Route::post('/admin/import_excel', [AlumnosController::class, 'ImportStudentsData'])->middleware('admin.auth')->middleware('transaction');
Route::delete('/admin/alumnos/{tuition}', [AlumnosController::class, 'DeleteStudent'])->middleware('admin.auth');
Route::get('/admin/export_excel/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AlumnosController::class, 'ExportStudentsData'])->middleware('admin.auth');
Route::get('/admin/alumnos/cant/{elements}/pag/{page}/search/{search}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AlumnosController::class, 'GetStudents'])->name('admin.students')
// ->middleware('admin.auth');
;

//AlumnosEntradasController
Route::post('/admin/import_hours', [AlumnosEntradasController::class, 'ImportStudentsHoursData'])->middleware('admin.auth')->middleware('transaction');
Route::get('/admin/export_hours/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AlumnosEntradasController::class, 'ExportStudentsHoursData'])->middleware('admin.auth');
Route::get('/admin/alumnos-entradas/cant/{elements}/pag/{page}/search/{search}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AlumnosEntradasController::class, 'GetStudentsChecks'])->name('admin.students-checks')
// ->middleware('admin.auth');
;

//ErrorController
Route::get('/error', [ErrorController::class, 'Handle'])->name('error');


//TEST
Route::get('/progreso', [AssessorsController::class, 'Progress'])->name('progress');//->middleware('auth.device');

//TEST
Route::get('/entradas', [AssessorsController::class, 'Hour'])->name('hour');//->middleware('auth.device');
