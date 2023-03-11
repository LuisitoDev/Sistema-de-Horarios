import React, { useEffect, useRef, useState } from 'react'
import ReactDOM from 'react-dom'
import View from '../../../utils/ViewEnum';
import usePagination from '../../../hooks/usePagination';
import {decimalHoursToHoursMinutes} from "../../../utils/TimeFunctions"


function decimalAdjust(type, value, exp) {
    // Si el exp no está definido o es cero...
    if (typeof exp === 'undefined' || +exp === 0) {
      return Math[type](value);
    }
    value = +value;
    exp = +exp;
    // Si el valor no es un número o el exp no es un entero...
    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0)) {
      return NaN;
    }
    // Shift
    value = value.toString().split('e');
    value = Math[type](+(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp)));
    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp));
}


if (!Math.round10) {
    Math.round10 = function(value, exp) {
      return decimalAdjust('round', value, exp);
    };
}


const HeaderProgreso = (props) => {

    const [isLoadingResquest, setIsLoadingRequest] = useState(true)

    const getPercentage = (totalHours, hoursService) => {
        if (totalHours === undefined || hoursService === undefined)
            return "0";

        if (hoursService === 0)
            return "0";

        return Math.round10(totalHours * 100 / hoursService, -1);
    }

    return(
        <>
                    <h1 className="text-center my-5 display-4">Progreso</h1>
                    <section className="stats-header">
                        <div className="row d-flex align-items-center">
                            <div className="col-3 px-1">
                                <div className='d-flex flex-column justify-content-around'>
                                    <h2 className="fs-6 text-center text-blue__color">Total de horas</h2>
                                    <span className="text-center">{decimalHoursToHoursMinutes(props.totalHours)}</span>
                                </div>
                            </div>
                            <div className="col-3 px-1">
                                <div className='d-flex flex-column justify-content-around'>
                                    <h2 className="fs-6 text-center text-blue__color">% de horas</h2>
                                    <span className="text-center">{getPercentage(props.totalHours, props.hoursService)}%</span>
                                </div>
                            </div>
                            <div className="col-3 px-1">
                                <div className='d-flex flex-column justify-content-around'>
                                    <h2 className="fs-6 text-center text-blue__color">Horas restantes</h2>
                                    <span className="text-center">{decimalHoursToHoursMinutes(props.hoursService - props.totalHours)}</span>
                                </div>
                            </div>
                            <div className="col-3 px-1">
                                <div className='d-flex flex-column justify-content-around'>
                                    <h2 className="fs-6 text-center text-blue__color">Horas pendientes</h2>
                                    <span className="text-center">{decimalHoursToHoursMinutes(props.pendingHours)}</span>
                                </div>
                            </div>
                        </div>
                    </section>

        </>
    )

}

export default HeaderProgreso;
