
<form method="post" action="index.php?view=addPerson">

    <table border="0">
        <tbody>
            <tr>
                <td><label >Imię</label></td>
                <td><input   type="text" name="firstName"/></td>
            </tr>
            <tr>
                <td>    <label >Nazwisko</label></td>
                <td>    <input  type="text" name="lastName"/></td>
            </tr>
            <tr>
                <td>Płeć</td>
                <td><input  type="radio" name="gender" value="women"/> <label>Kobieta</label></td>
            </tr>
            <tr>
                <td></td>
                <td><input   type="radio" name="gender" value="men"/>         <label>Mężczyzna</label></td>
            </tr>
            <tr>
                <td> <label>Nazwisko panieńskie</label></td>
                <td>    <input  type="text" name="maidenName"/></td>
            </tr>
            <tr>
                <td>    <label>Email</label></td>
                <td>    <input  type="text" name="email"/></td>
            </tr>
            <tr>
                <td>    <label>Kod pocztowy</label></td>
                <td>    <input  type="text" name="postalCode"/></td>
            </tr>
            <tr>
                <td></td>
                <td>    <input type="submit"/></td>
            </tr>
        </tbody>
    </table>

</form>