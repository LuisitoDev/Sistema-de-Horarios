import React, {useState, useRef} from 'react';
import ReactDOM from 'react-dom';
import MobileNavbar from "../General/MobileNavbar";
import View from '../../utils/ViewEnum';
import Alumnos from './Alumnos';
import Solicitudes from './Solicitudes';
import RelojAdmin from './RelojAdmin';
import { AnimatePresence, motion } from 'framer-motion';
import Header from '../General/Header';
import UserType from '../../utils/UserEnum';

const HomeAdmin = (props) => {
    const [view, setView] = useState(View.Alumnos);
    //Este es el tipo de usuario que se ha logeado, por ahora esta hardcodeado pero hay que ver si van a manejarlo los de back, es decir si nos lo van a retornar con una peticion
    //o lo manejaremos en el front, la verdad seria mas seguro que nos lo mandaran por el back
    const userType = UserType.Admin;
    const [selectedSchoolCycle, setSelectedSchoolCycle] = useState(0);

    const dayFrom = useRef(null);
    const dayTo = useRef(null);

    return(
        <>
            <div className="container-fluid min-vh-100">
                <Header setView={setView} userType={userType}
                        selectedSchoolCycle={selectedSchoolCycle} setSelectedSchoolCycle={setSelectedSchoolCycle}
                        dayFrom={dayFrom} dayTo={dayTo}/>
                <main className="row mx-2 mb-md-5 pt-lg-5 position-relative">
                    <AnimatePresence initial={false}>
                        { view === View.Alumnos && <Alumnos key={View.Alumnos} selectedSchoolCycle={selectedSchoolCycle} setSelectedSchoolCycle={setSelectedSchoolCycle} dayFrom={dayFrom} dayTo={dayTo}/>}
                        { view === View.Solicitudes && <Solicitudes key={View.Solicitudes} selectedSchoolCycle={selectedSchoolCycle} setSelectedSchoolCycle={setSelectedSchoolCycle} dayFrom={dayFrom} dayTo={dayTo}/>}
                        { view === View.RelojAdmin && <RelojAdmin key={View.RelojAdmin} selectedSchoolCycle={selectedSchoolCycle} setSelectedSchoolCycle={setSelectedSchoolCycle}/>}
                    </AnimatePresence>
                </main>
            </div>
            <MobileNavbar setView={setView} userType={userType}/>
        </>
    )
}

export default HomeAdmin;

if(document.getElementById('Home-Admin')){
    ReactDOM.render(<HomeAdmin/>, document.getElementById('Home-Admin'));
}
