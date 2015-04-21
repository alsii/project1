class FileDatabase inplements DatabaseInterface
{
    public function save($entity);
    {
        $time=sha1(microtime());
        $text=$this->serialize($entity);
        $class = get_class_name($entity);
    }
}
