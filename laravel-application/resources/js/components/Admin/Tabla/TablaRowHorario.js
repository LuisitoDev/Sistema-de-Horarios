import React from "react";

const TablaRowHorario= (props)=>{

    return(
        <>
        <tr className="text-blue__color " >
                    <td scope="row "><span>{props.day}</span></td>
                    <td className=""><select name="horario" className="p-1 ss-btn text-white ">
                    <option value="8:00">8:00</option>
                    <option value="8:30">8:30</option>

                    <option value="9:00">9:00</option>
                    <option value="9:30">9:30</option>

                </select></td>
                <td className=""><select name="horario" className="p-1 ss-btn text-white ">
                    <option value="8:00">8:00</option>
                    <option value="8:30">8:30</option>

                    <option value="9:00">9:00</option>
                    <option value="9:30">9:30</option>

                </select></td>
        </tr>
        </>
    )
}
export default TablaRowHorario;