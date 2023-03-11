import React, { useEffect, useRef, useState } from "react";
import ReactDOM from "react-dom";
import { UpdateProfilePicture } from "../../../../../services/AlumnosServices";
import Default from "/images/147144.png";
import ModalEngine from "../../../../General/ModalEngine";

const FotoPerfil = (props) => {
    const [img, setImg] = useState(props.imagen);

    //Modal
    const [isModalActive, setIsModalActive] = useState(false);
    const [modalMessage, setModalMessage] = useState("");

    const modalHandler=()=>{
        setIsModalActive(false);
    }

    const showUpExceptionModal = (exception) => {
        setModalMessage(exception.response.data.MESSAGE);
        setIsModalActive(true);
    }

    const handleFileUpload = async (e) => {
        const file = e.target.files[0];
        const base64 = await convertToBase64(file);
        var formData = new FormData();
        
        formData.append("imagen", base64);
    
        UpdateProfilePicture(formData).then((response)=>{
            console.log(response);
        }).catch((exception)=>{
            showUpExceptionModal(exception)
            console.log(exception);
        });
        setImg(base64);
      };
    const convertToBase64 = (file) => {
        return new Promise((resolve, reject) => {
          const fileReader = new FileReader();
          fileReader.readAsDataURL(file);
          fileReader.onload = () => {
            var result= fileReader.result.split(',')[1].trim();
            resolve(result);
            
            
            //console.log(result)
          };
         
          fileReader.onerror = (error) => {
            reject(error);
          };
        });
      };
      
    useEffect(() => {
        setImg(props.imagen);
        // console.log(props.imagen);
        // console.log("Estoy en foto perfil")
    }, []);

    return (
        <>
            <div className="profile-userpic mb-4">
                {img &&  <img className="mb-4" src={`data:image/png;base64,${img}`} alt="Avatar"  style={{width: '260px', height:'260px', borderRadius:'50%'}}/> }
                {!img &&  <img className="mb-4" src={Default} alt="Avatar" /> }
                <div className="d-flex justify-content-center mb-4">
                    <label
                        className="btn button-color text-white fs-6 px-3 py-1"
                        type="submit"
                        style={{
                            background: "#1e4ea1",
                            borderBottom: "5px solid",
                            borderBottomColor: "#163C7C",
                        }}
                    
                        htmlFor="upload"
                    >
                        <input
                            type="file"
                            className="form-control rounded-2"
                            onChange={(e) => handleFileUpload(e)}
                            style={{ display: "none" }}
                            id="upload"
                        />
                        Editar foto
                    </label>
                </div>
            </div>

            { isModalActive &&  <ModalEngine  modalHandler={modalHandler} setIsModalActive={setIsModalActive}
            title="Error"
            message={modalMessage}
        /> }
        </>
    );
};

export default FotoPerfil;
