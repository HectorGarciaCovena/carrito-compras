<?php
date_default_timezone_set("America/Lima");

// Incluye dos archivos, "La-carta.php" y "Configuracion.php".
// Crea una instancia de la clase "Cart" a partir del archivo "La-carta.php".
include 'La-carta.php';
$cart = new Cart;

// Se incluye archivo de configuración de base de datos
include 'Configuracion.php';

// Verifica si se ha enviado una solicitud con el parámetro 'action'.
// El código dentro de este bloque condicional maneja diferentes acciones basadas en el valor de 'action'.
if(isset($_REQUEST['action']) && !empty($_REQUEST['action'])){
    
    // Si la acción es 'addToCart' y se proporciona un 'id' 
    // se consulta la base de datos para obtener detalles del producto y se agrega al carrito.
    if($_REQUEST['action'] == 'addToCart' && !empty($_REQUEST['id'])){
        $productID = $_REQUEST['id'];
        
        // Obtiene detalles del Producto
        $query = $db->query("SELECT * FROM mis_productos WHERE id = ".$productID);
        $row = $query->fetch_assoc();
        $itemData = array(
            'id' => $row['id'],
            'name' => $row['name'],
            'price' => $row['price'],
            'qty' => 1
        );
        
        $insertItem = $cart->insert($itemData);
        $redirectLoc = $insertItem?'VerCarta.php':'index.php';
        header("Location: ".$redirectLoc);
    
    // Si la acción es 'updateCartItem' y se proporciona un 'id' 
    // se actualiza la cantidad de ese ítem en el carrito.
    }elseif($_REQUEST['action'] == 'updateCartItem' && !empty($_REQUEST['id'])){
        $itemData = array(
            'rowid' => $_REQUEST['id'],
            'qty' => $_REQUEST['qty']
        );
        $updateItem = $cart->update($itemData);
        echo $updateItem?'ok':'err';die;
    
    // Si la acción es 'removeCartItem' y se proporciona un 'id', se elimina ese ítem del carrito.
    }elseif($_REQUEST['action'] == 'removeCartItem' && !empty($_REQUEST['id'])){
        $deleteItem = $cart->remove($_REQUEST['id']);
        header("Location: VerCarta.php");
    
    // Si la acción es 'placeOrder', hay al menos un artículo en el carrito 
    // y hay un 'sessCustomerID' en la sesión, se procede a insertar los detalles del pedido en la base de datos.
    }elseif($_REQUEST['action'] == 'placeOrder' && $cart->total_items() > 0 && !empty($_SESSION['sessCustomerID'])){
        
        // se insertan los detalles de los artículos del carrito en la base de datos 
        // y se redirige a diferentes páginas dependiendo del resultado.
        $insertOrder = $db->query("INSERT INTO orden (customer_id, total_price, created, modified) VALUES ('".$_SESSION['sessCustomerID']."', '".$cart->total()."', '".date("Y-m-d H:i:s")."', '".date("Y-m-d H:i:s")."')");
        
        if($insertOrder){
            $orderID = $db->insert_id;
            $sql = '';
            
            // Se obtienen los artículos del carrito
            $cartItems = $cart->contents();
            foreach($cartItems as $item){
                $sql .= "INSERT INTO orden_articulos (order_id, product_id, quantity) VALUES ('".$orderID."', '".$item['id']."', '".$item['qty']."');";
            }
            
            // Se inserta el pedido realizado en la base de datos
            $insertOrderItems = $db->multi_query($sql);
            
            if($insertOrderItems){
                $cart->destroy();
                header("Location: OrdenExito.php?id=$orderID");
            }else{
                header("Location: Pagos.php");
            }
        }else{
            header("Location: Pagos.php");
        }
    }else{
        header("Location: index.php");
    }

// Si no se cumple ninguna de las condiciones anteriores, se redirige a "index.php".
}else{
    header("Location: index.php");
}