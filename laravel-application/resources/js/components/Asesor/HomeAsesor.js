import React, {useState, useEffect} from 'react';
import ReactDOM from 'react-dom';
import MobileNavbar from "../General/MobileNavbar";
import View from '../../utils/ViewEnum';

import { AnimatePresence, motion } from 'framer-motion';
import Header from '../General/Header';
import UserType from '../../utils/UserEnum';
import CargaHoras from './CargaHoras/CargaHoras';
import Progreso from './Progreso/Progreso';
import Perfil from './Perfil/Perfil';

const calculateMinSize = ()=> {
    const availableHeight = window.screen.availHeight
    return availableHeight - 160
}

const HomeAsesor= (props) => {
    const [view, setView] = useState(View.CargaHoras);
    const [minHeight, setMinHeight] = useState('80vh');

    useEffect(()=>{
        setMinHeight(calculateMinSize() + 'px');
    }, [])

    //Este es el tipo de usuario que se ha logeado, por ahora esta hardcodeado pero hay que ver si van a manejarlo los de back, es decir si nos lo van a retornar con una peticion
    //o lo manejaremos en el front, la verdad seria mas seguro que nos lo mandaran por el back
    const userType = UserType.Asesor;
    return(
        <>
            <div className="container-fluid root-container">
                <Header setView={setView} userType={userType}/>
                <main className="mx-2 position-relative" style={{minHeight}}>
                    <AnimatePresence initial={false}>
                        { view === View.Perfil && <Perfil key={View.Perfil}/> }
                        { view === View.CargaHoras && <CargaHoras key={View.CargaHoras}/>}
                        { view === View.Progreso && <Progreso key={View.Progreso}/>}

                    </AnimatePresence>
                </main>
            </div>
            <MobileNavbar setView={setView} userType={userType}/>
        </>
    )
}

export default HomeAsesor;

if(document.getElementById('Home-Asesor')){
    ReactDOM.render(<HomeAsesor/>, document.getElementById('Home-Asesor'));
}