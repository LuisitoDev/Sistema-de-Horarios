import React from 'react'
import View from './ViewEnum'

const ButtonTabs = (props) => {
    const { view, setView } = props;
    const buttonTabsClasses = `btn w-50 ss-btn-request mx-0`
    

    return(
    <>
        { (view === View.Solicitudes || view === View.SolicitudesDispositivos) &&
            <div className="w-100">
                <button className={`${view === View.Solicitudes ? 'text-white' : 'ss-btn-outline'} ${buttonTabsClasses}`} onClick={() => { setView(View.Solicitudes)} }>Usuarios</button>
                <button className={`${view === View.SolicitudesDispositivos ? 'text-white' : 'ss-btn-outline'} ${buttonTabsClasses}`} onClick={() => { setView(View.SolicitudesDispositivos)} }>Dispositivos</button>
            </div>
        }

        { (view === View.Alumnos || view === View.AlumnosEntradas) &&
                <div className="w-100">
                    <button className={`${view === View.Alumnos ? 'text-white' : 'ss-btn-outline'} ${buttonTabsClasses}`} onClick={() => { setView(View.Alumnos)} }>General</button>
                    <button className={`${view === View.AlumnosEntradas ? 'text-white' : 'ss-btn-outline'} ${buttonTabsClasses}`} onClick={() => { setView(View.AlumnosEntradas)} }>Entradas</button>
                </div>
        }
    </>
    )
}

export default ButtonTabs;