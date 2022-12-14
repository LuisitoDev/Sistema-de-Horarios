import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import Default from "/images/147144.png";
import FotoPerfil from "./FotoPerfil/FotoPerfil";
import DatoPerfil from "../DatoPerfil";

const InfoPerfil = (props) => {
  
    return (
        <>
            <FotoPerfil imagen={props.alumno.imagen}></FotoPerfil>
            <hr></hr>
            <div className="info d-flex flex-column mx-3">
                <DatoPerfil icon="fa-user" info={props.alumno.nombre + ' ' + props.alumno.apellido_pat + ' '+ props.alumno.apellido_mat}></DatoPerfil>
                <DatoPerfil icon="fa-hashtag" info={props.alumno.matricula}></DatoPerfil>
                <DatoPerfil icon="fa-at" info={props.alumno.correo}></DatoPerfil>
                <DatoPerfil icon="fa-landmark" info={props.alumno.abreviacion}></DatoPerfil>
                <DatoPerfil icon="fa-paste" info={props.alumno.servicio}></DatoPerfil>
                {
                    props.alumno.programa &&  <DatoPerfil icon="fa-display" info={props.alumno.programa}></DatoPerfil>
                }
              
                
               
                
            </div>
        </>
    );
};

export default InfoPerfil;
