<?php
session_start();
class Node{
    public int $data;
    public ?Node $left = null;
    public ?Node $right = null;

    public function __construct(int $data) {
        $this->data = $data;
    }
}

class BST{
    public ?Node $head = null;
    public function add(int $data) {
        if ($this->head === null) {
            $this->head = new Node($data);
        } else {
            $this->insert($this->head, $data);
        }
    }

    private function insert(?Node $node, int $data) {
        if ($node === null) {
            return new Node($data);
        }

        if ($data < $node->data) {
            $node->left = $this->insert($node->left, $data);
        } elseif ($data > $node->data) {
            $node->right = $this->insert($node->right, $data);
        }

        return $node;
    }

    public function inorder(?Node $node = null) {
        if ($node === null) {
            $node = $this->head;
        }

        if ($node->left !== null) {
            $this->inorder($node->left);
        }
        echo $node->data . ' ';
        if ($node->right !== null) {
            $this->inorder($node->right);
        }
    }

    public function delete(int $data) {
        $this->head = $this->deleteNode($this->head, $data);
    }

    private function deleteNode(?Node $node, int $data): ?Node {
        if ($node === null) {
            return null;
        }
        
        if ($data < $node->data) {
            $node->left = $this->deleteNode($node->left, $data);
        } elseif ($data > $node->data) {
            $node->right = $this->deleteNode($node->right, $data);
        } else {
            if ($node->left === null) {
                return $node->right;
            } elseif ($node->right === null) {
                return $node->left;
            }

            $node->data = $this->minValue($node->right);
            $node->right = $this->deleteNode($node->right, $node->data);
        }

        return $node;
    }

    private function minValue(Node $node): int {
        $minv = $node->data;
        while ($node->left !== null) {
            $minv = $node->left->data;
            $node = $node->left;
        }
        return $minv;
    }
}

global $tree;

$tree = array();

function prettyPrint(?Node $root,string $path,array $sizes){
    if($root === null) return;
    global $tree;
    
    $prefix = "";

    if(strlen($path)>0){
        for($i=1;$i<strlen($path);$i++){
            if($path[$i] == $path[$i-1]){
                $prefix.=str_repeat(" ",$sizes[$i-1]+1);
            }
            else{
                $prefix.=str_repeat(" ",$sizes[$i-1])."|";
            }
        }  

        if($path[-1]=="L"){
            $prefix .= str_repeat(" ",$sizes[count($sizes)-1])."?";
        }
        else{
            $prefix .= str_repeat(" ",$sizes[count($sizes)-1])."*";
        }
    }

    $postfix = " .-+"[($root->left  != null ? 1 : 0)  + ($root->right != null ? 2 : 0)];
    $sizes[] = strlen($root->data);
    prettyPrint($root->left, $path."L",$sizes);
    array_splice($tree,0,0,$prefix.strval($root->data).$postfix);
    prettyPrint($root->right, $path."R",$sizes);
}







// unset($_SESSION["arr"]);
if (!isset($_SESSION["arr"])) {
    $_SESSION["arr"] = array(); 
}

$k = new BST();
$val = $_POST["val"] ?? null; 
$option = $_POST["option"] ?? null;



// $_SESSION["arr"][] =3;
// $_SESSION["arr"][] =4;
// $_SESSION["arr"][] =5;

if ($option === "1" && $val !== null && !in_array($val, $_SESSION["arr"])) {
    $_SESSION["arr"][] = $val;
} elseif ($option === "2") {
    $_SESSION["arr"] = array_diff($_SESSION["arr"], [$val]);
}

$s = '';

foreach($_SESSION["arr"] as $key => $v){
    $s .=$v.' ';
    $k->add($v);
}

prettyPrint($k->head,"",array());

$tree[]=$s;


$jsonResponse = json_encode($tree);

header('Content-Type: application/json;charset=utf-8');

echo $jsonResponse;

?>