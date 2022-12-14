import PreviousMap from 'postcss/lib/previous-map';
import React from 'react'
import TablaRowSolicitud from './TablaRowSolicitud';
import TablaRow from './TablaRowSolicitud';
import TablaRowAlumno from './TablaRowAlumno';
import TablaRowHoras from './TablaRowHoras';
import TablaRowHorario from './TablaRowHorario';
import TablaRowSolicitudDispositivo from './TablaRowSolicitudDispositivo';
import View from '../../../utils/ViewEnum';
import DayEnum from '../../../utils/DayEnum';
import {motion, AnimatePresence } from 'framer-motion';
import TablaRowAlumnosEntradas from './TablaRowAlumnosEntradas';
const TablaCuerpo = (props)=>{
    const days= DayEnum;
    const TablaRowSolicitudMotion= motion(TablaRowSolicitud);


    return(
        <tbody className="bg-white align-middle">

        {props.view===View.Alumnos && <>
        {
            props.content.map((student) => (
                <TablaRowAlumno key={student.tuition + student.program} student={student} onActions={props.onActions}/>
            ))
        }
        </>}

        {props.view === View.AlumnosEntradas && <>
        {
            props.content.map((student) => (
                <TablaRowAlumnosEntradas key={student.tuition + student.program} student={student}/>
            ))
            // <>
            //     <TablaRowAlumnosEntradas key={1}/>
            //     <TablaRowAlumnosEntradas key={2}/>
            //     <TablaRowAlumnosEntradas key={3}/>
            // </>
        }
        </>}

        {props.view===View.Solicitudes &&  <AnimatePresence>
        {


            props.content.map((request) => (
                <TablaRowSolicitudMotion initial={{ x: -300, opacity: 0 }}
                animate={{ x: 0, opacity: 1, }}
                exit={{ x: 100, opacity: 0 }} key={request.id} request={request} onActions={props.onActions}/>
            ))

        }
         </AnimatePresence>
        }

        {props.view===View.SolicitudesDispositivos && <>
        {
            props.content.map((request) => (
                <TablaRowSolicitudDispositivo key={request.tuition + request.program} request={request} onActions={props.onActions}/>
            ))
        }
        </>}

        {props.view===View.Progreso && <>
        {
            props.content.map((hour) => (
                <TablaRowHoras key={hour.id} hour={hour}/>
            ))
        }

        </>}

        {

        props.view === View.Horario &&
            <>

            <TablaRowHorario day={days.Lunes}></TablaRowHorario>
            <TablaRowHorario day={days.Martes}></TablaRowHorario>
            <TablaRowHorario day={days.Miercoles}></TablaRowHorario>
            <TablaRowHorario day={days.Jueves}></TablaRowHorario>
            <TablaRowHorario day={days.Viernes}></TablaRowHorario>
            </>
        }

        </tbody>
    )
}
export default TablaCuerpo;
