import React, { useEffect, useRef, useState } from "react";
import InfoPerfil from "./InfoPerfil/InfoPerfil";
import ReactDOM from "react-dom";
import {
    variantsMobileSwipe,
    variantsWebFade,
} from "../../../utils/AnimationVariants";
import { useIsViewportLarge } from "../../../hooks/useMediaQuery";
import { motion } from "framer-motion";
import LoadingEngine from "../../General/LoadingEngine";
import ModalEngine from "../../General/ModalEngine";

const Perfil = (props) => {
    const [profile, setProfile] = useState([]);
    const [img, setImg] = useState();
    const [isLoadingRequest,setIsLoadingRequest] = useState(true);
    const isViewportLarge = useIsViewportLarge();

    //Modal
    const [isModalActive, setIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");

    const modalHandler=()=>{
        setIsModalActive(false);
    }

    const showUpExceptionModal = (exception) => {
        setIsLoadingRequest(false);
        if(!exception.response.data.MESSAGE === "") setModalMessage(exception.response.data.MESSAGE);
        else setModalMessage("Hubo un error en el servidor")
        setIsModalActive(true);
    }

    const getUserInfo= ()=>{
        axios.get('/profile').then(res => {
            setProfile(res.data.usuario);
            setIsLoadingRequest(false)
        }).catch((exception)=>{
            console.log(exception); 
            showUpExceptionModal(exception)
        });
        
    }
    useEffect(() => {
        getUserInfo();
    },[]);

    const profileDummy = 
        {
            id: 1,
            matricula: "1844444",
            nombre: "Fernando Moncayo Marquez",
            correo: "fernando.moncayomz@uanl.edu.mx",
            carrera: "LMAD",
            servicio: "Servicio Social",
            programa: "Asesor",
        };

    // useEffect(() => {
    //     setProfile(profileDummy);
    // }, []);

    return (
        <>
        {isLoadingRequest &&  <LoadingEngine/>}
        {!isLoadingRequest && <>{!isViewportLarge && (
                <motion.div
                    className="row justify-content-center pt-lg-5"
                    variants={variantsMobileSwipe}
                    initial="swipeOutToRight"
                    animate="swipeIn"
                    exit="swipeOutToRight"
                >
                    <div className="col-12 col-sm-7 col-md-5 col-lg-5 col-xl-4">
                        <h4 className="text-center h2 my-3 display-1">
                            Perfil
                        </h4>
                        <div className="row justify-content-center">
                            <div className="col-12 col-sm-7 col-md-5 col-lg-5 col-xl-4">
                                    <InfoPerfil
                                        alumno={profile}
                                    ></InfoPerfil>
                                <br></br>
                            </div>
                        </div>
                    </div>
                </motion.div>
            )}
            {isViewportLarge && (
                <motion.div
                    className="container pt-lg-5"
                    variants={variantsWebFade}
                    initial="fadeOut"
                    animate="fadeIn"
                    exit="fadeOut"
                >
                    <h4 className="text-center h2 my-5 display-1">Perfil</h4>
                    <div className="row justify-content-center">
                        <div className="col-12 col-sm-7 col-md-5 col-lg-5 col-xl-4">
                          
                                <InfoPerfil
                                alumno={profile}
                            ></InfoPerfil>
                        </div>
                    </div>
                </motion.div>
            )} </> }
            
            { isModalActive &&  <ModalEngine  modalHandler={modalHandler} setIsModalActive={setIsModalActive}
            title="Error"
            message={modalMessage}
            /> }
        </>
    );
};

export default Perfil;

if (document.getElementById("Perfil")) {
    ReactDOM.render(<Perfil />, document.getElementById("Perfil"));
}
