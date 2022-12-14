<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AssessorsController;
use App\Http\Controllers\EntradaController;
use App\Http\Controllers\ErrorController;
use App\Http\Controllers\TurnoDiarioController;
use Illuminate\Support\Facades\Route;

// Route::get('/admin/login', function () {
//     return view('admin.login');
// });

// Route::get('/progreso', function (){
//     return view('asesor.progreso');
// });
// Route::get('/alumnos', function (){
//     return view('admin.alumnos-registrados');
// });
// Route::get('/solicitudes', function (){
//     return view('admin.alumnos-solicitudes');
// });
//  Route::get('/carga-horas', function (){
//      return view('asesor.carga-horas');
//  });
// Route::get('/editar-horario', function (){
//     return view('admin.editar-horario');
// });

// Route::get('/admin/home', function (){
//     return view('admin.home-admin');
// });
// Route::get('/asesor/home', function (){
//     return view('asesor.home-asesor');
// });

// Route::get('/test/admin', function (){
//     return view('admin.home-admin');
// });

// Route::get('/test/asesor', function (){
//     return view('asesor.home-asesor');
// });

// Route::get('/test/registro', function (){
//     return view('asesor.registro');
// });

// ============ ASSESSORS ROUTES ============
Route::get('/' , [AssessorsController::class, 'Index'])->name('assessors')->middleware('auth.device');

Route::get('/registro', [AssessorsController::class, 'Register'])->name('assessors.register');

Route::get('/registro/dispositivo', [AssessorsController::class, 'AddDevice'])->name('assessors.register.device');

Route::get('/reloj', [AssessorsController::class, 'Clock'])->name('clock')->middleware('auth.device');

Route::get("/profile",[AssessorsController::class,'Profile'])->name('profile');//->middleware('auth.device');

Route::post("/profile",[AssessorsController::class,'UpdateProfilePicture'])->name('profile.update.profile.picture');

Route::get('/progreso_horas/cant/{elements}/pag/{page}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AssessorsController::class, 'MyHours'])->name('hours_progress');//->middleware('auth.device');

Route::get('/progreso_general/cant/{elements}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AssessorsController::class, 'MyProgress'])->name('general_progress');//->middleware('auth.device');

Route::post('/signin-device', [AssessorsController::class, 'SignIn'])->name('assessors.signin')->middleware('transaction');

// ============ ADMIN ROUTES ============

Route::post('/admin/set-id-ciclo-escolar', [AdminController::class, 'SetIdCicloEscolarSession']);

Route::get('/admin/get-id-ciclo-escolar', [AdminController::class, 'GetIdCicloEscolarSession']);


Route::get('/admin', [AdminController::class, 'Index'])->name('admin');

Route::get('/admin/login', [AdminController::class, 'Login'])->name('admin.login');

Route::post('/admin/signin', [AdminController::class, 'SignIn'])->name('admin.signin');

Route::post('/admin/logout', [AdminController::class, 'Logout'])->name('admin.logout');

Route::get('/admin/ciclo-escolar', [AdminController::class, 'GetCicloEscolar'])->name('admin.school.cycles')->middleware('admin.auth');

// Se pasa la cantidad de elementos por pagina y la pagina en cuestion que se necesita
Route::get('/admin/solicitudes/dispositivos/cant/{elements}/pag/{page}/selected_school_cycle/{selected_school_cycle}', [AdminController::class, 'DeviceRequests'])->name('admin.requests.devices')
->middleware('admin.auth');

Route::post('/admin/solicitudes/dispositivos', [AdminController::class, 'AcceptRequest'])->name('admin.requests.devices.accept')->middleware('admin.auth')->middleware('transaction');
Route::delete('/admin/solicitudes/dispositivos', [AdminController::class, 'RejectRequest'])->name('admin.requests.device.reject')->middleware('admin.auth');


Route::post('/admin/import_excel', [AdminController::class, 'ImportStudentsData'])->middleware('admin.auth')->middleware('transaction');
Route::get('/admin/export_excel/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AdminController::class, 'ExportStudentsData'])->middleware('admin.auth');

Route::post('/admin/import_hours', [AdminController::class, 'ImportStudentsHoursData'])->middleware('admin.auth')->middleware('transaction');
Route::get('/admin/export_hours/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AdminController::class, 'ExportStudentsHoursData'])->middleware('admin.auth');


Route::get('/admin/alumnos/cant/{elements}/pag/{page}/search/{search}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AdminController::class, 'GetStudents'])->name('admin.students')
// ->middleware('admin.auth');
;

Route::get('/admin/alumnos-entradas/cant/{elements}/pag/{page}/search/{search}/fecha-desde/{dayFrom}/fecha-hasta/{dayTo}', [AdminController::class, 'GetStudentsChecks'])->name('admin.students-checks')
// ->middleware('admin.auth');
;

Route::delete('/admin/alumnos/{tuition}', [AdminController::class, 'DeleteStudent'])->middleware('admin.auth');
Route::delete('/admin/delete_old_data', [AdminController::class, 'DeleteOldData'])->middleware('admin.auth');

// ============ ENTRADA ROUTES ============
Route::post('/entrada/registrar-hora-entrada', [EntradaController::class, 'RegistarHoraEntrada'])->name('entrada.registar-hora-entrada')->middleware('transaction');
Route::put('/entrada/registrar-hora-salida', [EntradaController::class, 'RegistarHoraSalida'])->name('entrada.registar-hora-salida')->middleware('transaction');
Route::get('/entrada/obtener-entrada-diaria', [EntradaController::class, 'ObtenerEntradaDiaria'])->name('entrada.obtener-entrada-diaria');

//TEST
Route::get('/progreso', [AssessorsController::class, 'Progress'])->name('progress');//->middleware('auth.device');

//TEST
Route::get('/entradas', [AssessorsController::class, 'Hour'])->name('hour');//->middleware('auth.device');


Route::get('/error', [ErrorController::class, 'Handle'])->name('error');
