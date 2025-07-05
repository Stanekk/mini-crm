<?php

namespace App\Controller;

use App\Entity\Company;
use App\Mapper\CompanyMapper;
use App\Repository\CompanyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompanyController extends AbstractController
{
    #[Route('/api/companies', name: 'app_companies', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository, CompanyMapper $companyMapper): JSONResponse
    {
        $companies = $companyRepository->findAll();
        $companiesDto = array_map(fn(Company $company) => $companyMapper->toDto($company), $companies);
        
        return new JsonResponse($companiesDto, Response::HTTP_OK);

    }
}