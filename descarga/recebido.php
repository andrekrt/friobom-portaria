<?php
require("../conexao.php");

if(isset($_POST['confirmar']) && isset($_POST['formaPag']) && !empty($_POST['formaPag'])){
    $token = filter_input(INPUT_POST, 'tokenRecebido');
    $data = date('Y-m-d H:i');
    $formaPag = filter_input(INPUT_POST, 'formaPag');

    $db->beginTransaction();

    try{
        $sql = $db->prepare("UPDATE descarga SET data_hora_recebido = :dataRecebido, forma_recebimento=:recebimento, confirmacao_financeiro=:confirmacao WHERE token = :token" );
        $sql->bindValue(':dataRecebido', $data);
        $sql->bindValue(':token', $token);
        $sql->bindValue(':recebimento', $formaPag);
        $sql->bindValue(':confirmacao', 'Confirmado');
        $sql->execute();
        
        $db->commit();
        $_SESSION['msg'] = 'Recebido!';
        $_SESSION['icon']='success';

    }catch(Exception $e){
        $db->rollBack();
        $_SESSION['msg'] = 'Erro ao Atualizar';
        $_SESSION['icon']='error';
    }
    
    header("Location: descargas.php");
    exit();
}

?>

<!-- Modal Excluir -->
<div class="modal fade" id="modalRecebido" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloExcluir">Confirmar Recebimento</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <input type="hidden" id="tokenRecebido" name="tokenRecebido">
                    <div class="form-row">
                        <div class="form-group col-md-12 ">
                            <label for="formaPag">Forma de Pagamento</label>
                            <select name="formaPag" id="formaPag" class="form-control">
                                <option value=""></option>
                                <option value="PIX">PIX</option>
                                <option value="Dinheiro">Dinheiro</option>
                                <option value="Desconto em Título">Desconto em Título</option>
                            </select>
                        </div>                                      
                    </div>    
            </div>
            <div class="modal-footer">
                <button type="submit" name="confirmar" class="btn btn-primary">Confirmar</button>
                </form>
            </div>
        </div>
    </div>
</div>