import React, { useEffect, useState } from 'react';
import {createDateFromString} from "../../utils/TimeFunctions"
import DatePicker from "react-multi-date-picker";

const DatePickerEngine = (props) => {

    const { valueCalendar, setValueCalendar, applyFilter, dayFrom, dayTo } = props;

    const clickHandlerButtonFilter = (e) => {
        e.preventDefault();
        if (!Array.isArray(valueCalendar)){
            dayFrom.current = valueCalendar;
            dayTo.current =  valueCalendar;
        }
        else {
            if (valueCalendar.length === 1){
                if (valueCalendar[0] !== undefined && valueCalendar[0] !== null){
                    let datePicker = undefined;
                    if (valueCalendar[0] instanceof Date){
                        datePicker = `${valueCalendar[0].getFullYear()}-${valueCalendar[0].getMonth()  + 1}-${valueCalendar[0].getDate()}`;
                    }
                    else{
                        datePicker = `${valueCalendar[0].year}-${valueCalendar[0].month.number}-${valueCalendar[0].day}`;
                    }

                    dayFrom.current = datePicker;
                    dayTo.current =  datePicker;
                }
            }
            else{

                if (valueCalendar[0] !== undefined && valueCalendar[0] !== null){
                    if (valueCalendar[0] instanceof Date){
                        dayFrom.current = `${valueCalendar[0].getFullYear()}-${valueCalendar[0].getMonth() + 1}-${valueCalendar[0].getDate()}`;
                        console.log(dayFrom.current);
                    }
                    else{
                        dayFrom.current = `${valueCalendar[0].year}-${valueCalendar[0].month.number}-${valueCalendar[0].day}`;
                    }
                }

                if (valueCalendar[1] !== undefined && valueCalendar[1] !== null)
                    if (valueCalendar[1] instanceof Date){
                        dayTo.current = `${valueCalendar[1].getFullYear()}-${valueCalendar[1].getMonth()  + 1}-${valueCalendar[1].getDate()}`;
                        console.log(dayTo.current);
                    }
                    else
                        dayTo.current = `${valueCalendar[1].year}-${valueCalendar[1].month.number}-${valueCalendar[1].day}`;
            }
        }

        applyFilter();
    }

    useEffect(() => {

        let finalDateFrom = createDateFromString(dayFrom.current);
        let finalDateto = createDateFromString(dayTo.current);

        setValueCalendar([finalDateFrom, finalDateto]);

    }, [dayFrom, dayTo]);

    return(
        <>
            <p className='my-0 mx-2 '>Rango de Fechas:</p>
            <DatePicker value={valueCalendar} onChange={setValueCalendar} range={true} format ={"DD/MM/YYYY"}/>
            <button type="button" className="button-color  border-0 rounded text-light py-1 px-2 mx-2" onClick={clickHandlerButtonFilter}>
                Apply
            </button>
        </>
    )
}

export default DatePickerEngine;

