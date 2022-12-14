import React, { useEffect, useState } from 'react'
import { readableDateMonthYear } from '../../../utils/TimeFunctions'
import * as AdminServices from '../../../services/AdminServices';

const CicloEscolar = (props) => {

    const { dayFrom, dayTo } = props;

    const [schoolCycles, setSchoolCycles] = useState([]);
    const selectedSchoolCycle = props.selectedSchoolCycle;

    useEffect(() => {
        AdminServices.GetSchoolCycles().then(response => {
            setSchoolCycles(response.data.ciclosEscolares);

            dayFrom.current = response.data.ciclosEscolares[0].fecha_ingreso;
            dayTo.current = response.data.ciclosEscolares[0].fecha_salida;

            //TODO: HAY UN BUG AL HACER ESTO EN LA VERSION MOBILE, ARREGLAR
            props.setSelectedSchoolCycle(response.data.ciclosEscolares[0].id);
        }).catch(error => {
            //TODO: "setError" Y "setErrdesc" NO ESTA DEFINIDO
            setError(true)
            setErrdesc(error.response.data.MESSAGE)
        })
    }, [])

    const handleChange = (e) => {
        props.setSelectedSchoolCycle(e.target.value);

        let schoolCycleElegido = null;

        for(var i = 0; i < schoolCycles.length; i++) {
            if (schoolCycles[i].id == e.target.value) {
                schoolCycleElegido = schoolCycles[i];
                break;
            }
        }

        dayFrom.current = schoolCycleElegido?.fecha_ingreso;
        dayTo.current = schoolCycleElegido?.fecha_salida;


    };

    return(
    <>
        <select className="schoolCycle text-white border-0 w-100 p-1 rounded" onChange={handleChange} value={selectedSchoolCycle} name="select">
            {schoolCycles &&
                schoolCycles.map((schoolCycle) => (
                    <option key={schoolCycle.id} value={schoolCycle.id}>{readableDateMonthYear(schoolCycle.fecha_ingreso) + " - " +  readableDateMonthYear(schoolCycle.fecha_salida)}</option>
                ))
            }
        </select>
        <hr  style={{height:'2px',borderWidth:'100%',color:'gray',backgroundColor:'rgb(12, 12, 12)'}}/>
    </>
    )
}

export default CicloEscolar;

