<?php
namespace asbamboo\router;

class Route implements RouteInterface
{
    /**
     *
     * @var string
     */
    protected $id;

    /**
     *
     * @var string
     */
    protected $scheme;

    /**
     *
     * @var string
     */
    protected $host;

    /**
     *
     * @var string
     */
    protected $path;

    /**
     *
     * @var callable
     */
    protected $callback;

    /**
     *
     * @var array|null
     */
    protected $default_params;

    /**
     *
     * @var array|null
     */
    protected $options;

    /**
     *
     * @param string $id
     * @param string $path
     * @param callable $callback
     * @param array $default_params
     * @param array $options
     */
    public function __construct(string $id, string $path, callable $callback, array $default_params = null, array $options = null)
    {
        $this->id               = $id;
        $this->path             = $path;
        $this->callback         = $callback;
        $this->default_params   = $default_params;
        $this->options          = $options;

        if(isset($this->options['host'])){
            $this->host   = $this->options['host'];
        }elseif(isset($_SERVER['HTTP_HOST'])){
            $this->host = $_SERVER['HTTP_HOST'];
        }

        if(!empty($this->host)){
            if(isset($this->options['scheme'])){
                $this->scheme   = $this->options['scheme'];
            }elseif(isset($_SERVER['REQUEST_SCHEME'])){
                $this->scheme   = $_SERVER['REQUEST_SCHEME'];
            }else{
                $this->scheme   = "http";
            }
        }
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getId()
     */
    public function getId() : string
    {
        return $this->id;
    }


    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getScheme()
     */
    public function getScheme() : string
    {
        return (string) $this->scheme;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getHost()
     */
    public function getHost() : string
    {
        return (string) $this->host;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getPath()
     */
    public function getPath() : string
    {
        return rtrim($this->path, '/');
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getCallback()
     */
    public function getCallback() : callable
    {
        return $this->callback;
    }


    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getDefaultParams()
     */
    public function getDefaultParams() : ?array
    {
        return $this->default_params;
    }

    /**
     *
     * {@inheritDoc}
     * @see \asbamboo\router\RouteInterface::getOptions()
     */
    public function getOptions() : ?array
    {
        return $this->options;
    }
}