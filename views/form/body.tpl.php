
<form method="post" action="addUser.php">

    <label >Imię</label>
    <input   type="text" name="firstName"/>
    <br/>

    <label >Nazwisko</label>
    <input  type="text" name="lastName"/>
    <br/>

    <p>Płeć</p>
    <input  type="radio" name="sex" value="women"/> <label>Kobieta</label><br/>
    <input   type="radio" name="sex" value="men"/>         <label>Mężczyzna</label><br/>
    <br/>
    <label>Nazwisko panieńskie</label>
    <input  type="text" name="maidenName"/>
    <br/>
    <label>Email</label>
    <input  type="text" name="email"/>
    <br/>
    <label>Kod pocztowy</label>
    <input  type="text" name="postalCode"/>
    <br/>
    <input type="submit"/>
</form>