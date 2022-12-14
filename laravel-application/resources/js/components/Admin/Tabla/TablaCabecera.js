import React from 'react';
import View from '../../../utils/ViewEnum';

const TablaCabecera = (props)=>{
    return (
    <thead className=" text-white p-2 box-blue__background" >
      <tr >
      {(props.view === View.Alumnos || props.view === View.Solicitudes) &&
        <>
          <th scope="col ">Matricula</th>
          <th className="d-none d-lg-table-cell" scope="col">Nombre</th>
          <th className="d-none d-lg-table-cell" scope="col">Correo</th>
          <th  className="d-none d-lg-table-cell" scope="col ">Programa</th>
          <th scope="col ">Carrera</th>
        </>
      }

      { props.view === View.Solicitudes &&
        <>
          <th scope='col'>Folio</th>
          <th scope='col'>Dispositivo</th>
        </>
      }

      {(props.view === View.Alumnos || props.view === View.Solicitudes) &&
          <th scope="col ">Acciones</th>
      }

      {props.view === View.Progreso &&
        <>
          <th scope="col" className='text-center'>Fecha</th>
          <th scope="col" className='text-center'>Hora inicio program.</th>
          <th scope="col" className='text-center'>Hora final program.</th>
          <th scope="col" className='text-center'>Hora inicio</th>
          <th scope="col" className='text-center'>Hora final</th>
          <th scope="col" className='text-center'>Horas trabajadas</th>
        </>
      }

      {props.view === View.Horario &&

      <>
        <th scope="col  ">Dia</th>
        <th scope="col">De:</th>
        <th scope="col">Hasta</th>
      </>
      }

      {props.view === View.SolicitudesDispositivos &&

      <>
          <th scope="col">Matricula</th>
          <th className="d-none d-lg-table-cell" scope="col">Nombre</th>
          <th className="d-none d-lg-table-cell" scope="col">Correo</th>
          <th className="d-none d-lg-table-cell" scope="col ">Programa</th>
          <th className="d-none d-lg-table-cell" scope="col ">Carrera</th>
          <th className="d-none d-lg-table-cell" scope="col ">Folio</th>
          <th scope="col ">Dispositivo</th>
          <th scope="col ">Acciones</th>
      </>
      }

      {props.view === View.AlumnosEntradas &&
        <>
          <th scope="col ">Matricula</th>
          <th className="d-none d-lg-table-cell" scope="col">Nombre</th>
          <th className="d-none d-lg-table-cell" scope="col">Correo</th>
          <th className="d-none d-lg-table-cell" scope="col ">Programa</th>
          <th className='d-none d-lg-table-cell' scope="col ">Carrera</th>
          <th scope="col ">Entradas</th>
          <th scope="col ">Horas por reponer</th>
        </>
      }

      </tr>

  </thead>)
}
export default TablaCabecera;
