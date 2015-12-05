<?php

namespace Ais\WisudaBundle\Model;

Interface WisudaInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set matakuliahId
     *
     * @param integer $matakuliahId
     *
     * @return Wisuda
     */
    public function setMatakuliahId($matakuliahId);

    /**
     * Get matakuliahId
     *
     * @return integer
     */
    public function getMatakuliahId();

    /**
     * Set mahasiswaId
     *
     * @param integer $mahasiswaId
     *
     * @return Wisuda
     */
    public function setMahasiswaId($mahasiswaId);

    /**
     * Get mahasiswaId
     *
     * @return integer
     */
    public function getMahasiswaId();

    /**
     * Set semesterId
     *
     * @param integer $semesterId
     *
     * @return Wisuda
     */
    public function setSemesterId($semesterId);

    /**
     * Get semesterId
     *
     * @return integer
     */
    public function getSemesterId();

    /**
     * Set kelasId
     *
     * @param integer $kelasId
     *
     * @return Wisuda
     */
    public function setKelasId($kelasId);

    /**
     * Get kelasId
     *
     * @return integer
     */
    public function getKelasId();

    /**
     * Set nilaiTugas
     *
     * @param string $nilaiTugas
     *
     * @return Wisuda
     */
    public function setNilaiTugas($nilaiTugas);

    /**
     * Get nilaiTugas
     *
     * @return string
     */
    public function getNilaiTugas();

    /**
     * Set nilaiUas
     *
     * @param string $nilaiUas
     *
     * @return Wisuda
     */
    public function setNilaiUas($nilaiUas);

    /**
     * Get nilaiUas
     *
     * @return string
     */
    public function getNilaiUas();

    /**
     * Set nilaiUts
     *
     * @param string $nilaiUts
     *
     * @return Wisuda
     */
    public function setNilaiUts($nilaiUts);

    /**
     * Get nilaiUts
     *
     * @return string
     */
    public function getNilaiUts();

    /**
     * Set nilaiHuruf
     *
     * @param string $nilaiHuruf
     *
     * @return Wisuda
     */
    public function setNilaiHuruf($nilaiHuruf);

    /**
     * Get nilaiHuruf
     *
     * @return string
     */
    public function getNilaiHuruf();

    /**
     * Set nilaiAngka
     *
     * @param string $nilaiAngka
     *
     * @return Wisuda
     */
    public function setNilaiAngka($nilaiAngka);

    /**
     * Get nilaiAngka
     *
     * @return string
     */
    public function getNilaiAngka();

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return Wisuda
     */
    public function setIsActive($isActive);

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive();

    /**
     * Set isDelete
     *
     * @param boolean $isDelete
     *
     * @return Wisuda
     */
    public function setIsDelete($isDelete);

    /**
     * Get isDelete
     *
     * @return boolean
     */
    public function getIsDelete();
}
