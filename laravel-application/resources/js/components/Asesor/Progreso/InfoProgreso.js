import React, { useEffect, useState } from 'react';
import usePagination from '../../../hooks/usePagination';
import Paginacion from '../../Admin/Paginación/Paginacion';
import Tabla from '../../Admin/Tabla/Tabla';
import DatePickerEngine from '../../General/DatePickerEngine';

const InfoProgreso = (props) => {

    const { view } = props;

    const { valueCalendar, setValueCalendar, applyFilter, dayFrom, dayTo } = props;

    return(
        <section className="progress-dashboard pb-5 pb-xl-0">
            <div className="row align-content-center mt-4">
                <div className="col">
                    <p className="ms-3 mb-0 fw-bold fs-5">Tus horas</p>
                </div>
            </div>

            <div className="row align-content-center">
                <div className="col d-flex justify-content-end align-items-center">
                    <DatePickerEngine valueCalendar={valueCalendar} setValueCalendar={setValueCalendar} applyFilter={applyFilter} dayFrom={dayFrom} dayTo={dayTo} />

                </div>
            </div>

            <Tabla view={view} content={props.hours}/>
            <Paginacion totalPages={props.totalPages} currentPage={props.currentPage}
            NextPageHandler={props.NextPageHandler} PrevPageHandler={props.PrevPageHandler}/>
        </section>
    )
}

export default InfoProgreso;

