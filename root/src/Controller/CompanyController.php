<?php

namespace App\Controller;

use App\Dto\CreateCompanyRequestDto;
use App\Entity\Company;
use App\Mapper\CompanyMapper;
use App\Repository\CompanyRepository;
use App\Service\CompanyService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CompanyController extends AbstractController
{
    private CompanyService $companyService;
    private CompanyMapper $companyMapper;
    private CompanyRepository $companyRepository;
    private ValidatorInterface $validator;

    public function __construct(CompanyService $companyService, CompanyMapper $companyMapper, CompanyRepository $companyRepository, ValidatorInterface $validator)
    {
        $this->companyService = $companyService;
        $this->companyMapper = $companyMapper;
        $this->companyRepository = $companyRepository;
        $this->validator = $validator;
    }

    #[Route('/api/companies', name: 'app_company_list', methods: ['GET'])]
    public function index(): JSONResponse
    {
        $companies = $this->companyRepository->findAll();
        $companiesDto = array_map(fn(Company $company) => $this->companyMapper->toDto($company), $companies);
        
        return new JsonResponse($companiesDto, Response::HTTP_OK);
    }

    #[Route('/api/companies', name: 'app_company_create', methods: ['POST'])]
    public function create(#[MapRequestPayload(validationGroups: ['Default'])] CreateCompanyRequestDto $dto): JSONResponse
    {
        $company = $this->companyService->create($dto);
        $responseDto = $this->companyMapper->toDto($company);

        return new JsonResponse($responseDto, Response::HTTP_CREATED);
    }
}