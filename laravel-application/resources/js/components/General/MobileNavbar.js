import React, {useEffect, useState} from "react";
import ReactDOM from 'react-dom';
import View from "../../utils/ViewEnum";
import UserType from '../../utils/UserEnum';

const getFooterHeight = () => {
    const availableHeight = window.screen.availHeight

    const footerSize =  availableHeight * 0.2

    return footerSize
}

const MobileNavbar = (props) => {
    const [footerSize, setFooterSize] = useState(80)
    
    useEffect(()=> {
        setFooterSize(getFooterHeight())
    }, [])



    return(
        <div className="container-fluid" >
            <footer className="row position-fixed bottom-0 vw-100 d-lg-none button-color" style={{height:"70px"}} id="mobileFooter">
                
                { props.userType === UserType.Admin && 
                <>
                {/* <a href="/admin/login" className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0" ><i className="text-white fs-1 fa-solid fa-arrow-right-from-bracket"></i></h1></a> */}
                <button onClick={() => { props.setView(View.Alumnos) }} className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0"><i className="text-white fa-solid fa-user-large"></i></h1></button>
                <button onClick={() => { props.setView(View.Solicitudes)}} className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0"><i className="text-white fa-solid fa-envelope"></i></h1></button>
                <button onClick={() => { props.setView(View.RelojAdmin)}} className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0"><i className="text-white fa-solid fa-clock"></i></h1></button>

                </>
                
                }
                { props.userType === UserType.Asesor && 
                <>
                <button onClick={() => { props.setView(View.Perfil) }} className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0"><i className="text-white fa-solid fa-user"></i></h1></button>
                <button onClick={() => { props.setView(View.CargaHoras)}} className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0"><i className="text-white fa-solid fa-clock"></i></h1></button>
                <button onClick={() => { props.setView(View.Progreso) }} className="col-4 d-flex btn justify-content-center align-items-center shadow-none" style={{height:"70px"}}><h1 className="m-0"><i className="text-white fa-solid fa-chart-column"></i></h1></button>

                </>
                
                }
            </footer>
            
            <footer className="d-none d-lg-block">       
                <div className="row">
                    <div className="col-12 button-color d-flex justify-content-center align-items-center" style={{height: "80px"}}>
                        <h5 className="text-white text-center m-0">Departamento de asesorías FCFM 2022 <i className="fa-regular fa-copyright text-white"></i></h5>
                    </div>   
                </div>
            </footer>
        </div>
    )
}

export default MobileNavbar;