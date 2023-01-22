 Sistema desenvolvido por:

  Juarez Monteiro de Vasconcelos Junior
 	Ricardo Souza de Carvalho

O sistema foi criado para gerenciar uma rede de agências bancárias. A aplicação deve permitir que um banco gerencie suas múltiplas agências. 
Cada agência tem apenas um gerente responsável, cada cliente pode ter várias contas em várias agências, onde só será possível movimentação a partir da
liberação da conta pelo gerente. As transações tradicionais devem ser capazes de ser realizadas entre contas, como debitar, creditar, e transferir. 
Uma  transação pode ser realizada pelo cliente associado com a conta ou pelo gerente responsável pela conta na agência. Todas as transações realizadas, devem 
ser registradas, para prestação de contas.

##Qualquer usuários

Todos que acessarem o sistema deve ser capaz de visualizar a lista de agências bancárias e as informações básicas associadas com as agências, 
endereço e gerente responsável, entre outros. Podendo efetuar depósitos em qualquer conta válida do banco, simulando o que seria feito em um caixa eletrônico e pode 
fazer seu cadastro e abrir sua conta.

##Gerentes

Gerentes responsáveis por uma agência tem o poder de efetuar qualquer transação relacionada com contas que pertençam à tal agência, bem como de fazer alterações 
cadastrais nos dados da agência e clientes associados. O gerente também pode ver todo o histórico de transações da agência.

##Clientes

Um cliente pode iniciar transações a partir de suas contas, e pode ver a lista de transações associadas a cada uma de suas contas. 

##Visualizar extrato:

O cliente poderá visualizar seu extrato de transações bancárias. Encerrar conta: Um cliente do banco pode solicitar para encerrar uma de suas contas.

Projeto realizado para a disciplina de programação avançada em PHP da pós-graduação e residência em desenvolvimento de software da UFPE/EMPREL!
Este é um sistema desenvolvido com PHP e frameworks como o Symfony,Twig , além do banco de dados MySQL com a utilização de Docker .

Requisitos para aplicação

PHP 8.1+
Composer
MYSQL 5.7
Symfony 6
Docker Desktop

Instalação

baixar ou clonar https://github.com/Juarez-Monteiro/ProjetoPHP8.git

Executar os comandos:

1- symfony server:start
2- composer require symfony/runtime
3- docker compose up
4- symfony console doctrine:database:create
5- symfony console make:migration
6- symfony console doctrine:migrations:status
7- symfony console doctrine:migrations:migrate
8- symfony console doctrine:fixture:load


As principais classes e seus relacionamentos:


class Agencia
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $nomeAgencia ;


    #[ORM\Column(length: 255)]
    private ?string $codigo ;


    #[ORM\Column(length: 255)]
    private ?string $endereco ;


    #[ORM\OneToOne(inversedBy: 'agencia', cascade: ['persist', 'remove'])]
    private ?Gerente $gerente ;


    #[ORM\OneToMany(mappedBy: 'agencia', targetEntity: Conta::class)]
    private Collection $contas;



class Conta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?float $saldo = 0;


    #[ORM\Column]
    private ?int $numeroDaConta ;


    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?Agencia $agencia;






    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?User $user ;


    #[ORM\Column]
    private ?bool $status ;


    #[ORM\ManyToOne(inversedBy: 'contas')]
    private ?TipoConta $tipos ;


    #[ORM\OneToMany(mappedBy: 'contaDestino', targetEntity: Transacao::class)]
    private Collection $transacaos;


class Gerente
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\OneToOne(mappedBy: 'gerente', cascade: ['persist', 'remove'])]
    private ?Agencia $agencia = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private ?User $user = null;


class TipoConta
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 2)]
    private ?string $tipo ;


    #[ORM\OneToMany(mappedBy: 'tipos', targetEntity: Conta::class)]
    private Collection $contas;



class Transacao
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 255)]
    private ?string $descricao ;


    #[ORM\Column]
    private ?float $valor ;


    #[ORM\ManyToOne(inversedBy: 'transacaos')]
    private ?Conta $contaDestino ;


    #[ORM\ManyToOne(inversedBy: 'transacaos')]
    private ?Conta $contaOrigem = null;



class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(length: 180, unique: true)]
    private ?string $email;


    #[ORM\Column]
    private array $roles = [];


    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password ;


    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Conta::class)]
    private Collection $contas;


    #[ORM\Column(length: 255)]
    private ?string $nome ;


    #[ORM\Column]
    private ?bool $status = null;


    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

